<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Models\Announcement;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function checkout()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your pre-order basket is empty.');
        }

        // Group by farmer
        $groupedCart = [];
        foreach ($cart as $id => $item) {
            $farmerId = $item['farmer_id'];
            if (!isset($groupedCart[$farmerId])) {
                $farmer = User::with('market')->find($farmerId);
                $groupedCart[$farmerId] = [
                    'farmer' => $farmer,
                    'items' => [],
                    'subtotal' => 0,
                ];
            }
            $itemSubtotal = $item['price'] * $item['quantity'];
            $groupedCart[$farmerId]['items'][$id] = $item;
            $groupedCart[$farmerId]['subtotal'] += $itemSubtotal;
        }

        $customer = Auth::user();

        return view('orders.checkout', compact('groupedCart', 'customer'));
    }

    public function store(Request $request)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your basket is empty.');
        }

        $request->validate([
            'farmer_orders' => 'required|array',
            'farmer_orders.*.pickup_date' => 'required|date|after_or_equal:today',
            'farmer_orders.*.pickup_time_slot' => 'required|string',
            'farmer_orders.*.customer_notes' => 'nullable|string|max:500',
        ]);

        $customer = Auth::user();
        $createdOrders = [];

        DB::beginTransaction();
        try {
            // Group cart by farmer
            $cartByFarmer = [];
            foreach ($cart as $id => $item) {
                $cartByFarmer[$item['farmer_id']][] = $item;
            }

            foreach ($cartByFarmer as $farmerId => $items) {
                $farmer = User::findOrFail($farmerId);
                $pickupData = $request->input("farmer_orders.{$farmerId}");

                $totalAmount = 0;
                foreach ($items as $item) {
                    $totalAmount += $item['price'] * $item['quantity'];
                }

                // Calculate cutoff time: pickup_date 08:00 AM minus farmer cutoff_hours
                $pickupDateTime = Carbon::parse($pickupData['pickup_date'] . ' 08:00:00');
                $cutoffHours = $farmer->cutoff_hours ?? 12;
                $cutoffTime = (clone $pickupDateTime)->subHours($cutoffHours);

                $orderNumber = 'ML-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

                $order = Order::create([
                    'order_number' => $orderNumber,
                    'customer_id' => $customer->id,
                    'farmer_id' => $farmer->id,
                    'market_id' => $farmer->market_id,
                    'total_amount' => $totalAmount,
                    'order_status' => 'placed',
                    'pickup_date' => $pickupData['pickup_date'],
                    'pickup_time_slot' => $pickupData['pickup_time_slot'],
                    'cutoff_time' => $cutoffTime,
                    'customer_notes' => $pickupData['customer_notes'] ?? null,
                ]);

                foreach ($items as $item) {
                    $product = Product::findOrFail($item['id']);

                    // Verify & deduct stock
                    if ($product->stock_quantity < $item['quantity']) {
                        throw new \Exception("Insufficient stock for {$product->name}. Only {$product->stock_quantity} remaining.");
                    }

                    $product->decrement('stock_quantity', $item['quantity']);

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'unit' => $product->unit,
                        'quantity' => $item['quantity'],
                        'price' => $product->price,
                        'subtotal' => $item['price'] * $item['quantity'],
                    ]);
                }

                // In-app order confirmation alert for Customer
                Announcement::create([
                    'user_id' => $customer->id,
                    'title' => "Pre-Order #{$order->order_number} Confirmed!",
                    'message' => "Your pre-order for {$farmer->stall_name} is confirmed for pickup on {$order->pickup_date->format('M d, Y')} ({$order->pickup_time_slot}). Cash is settled in-person at pickup.",
                    'type' => 'order_status',
                    'link' => route('customer.orders.show', $order->id),
                ]);

                // In-app notification for Farmer
                Announcement::create([
                    'user_id' => $farmer->id,
                    'title' => "New Incoming Pre-Order #{$order->order_number}",
                    'message' => "{$customer->name} placed an order of $" . number_format($totalAmount, 2) . " for pickup on {$order->pickup_date->format('M d, Y')}.",
                    'type' => 'order_status',
                    'link' => route('farmer.orders'),
                ]);

                $createdOrders[] = $order;
            }

            DB::commit();
            session()->forget('cart');

            return redirect()->route('customer.orders.index')
                ->with('success', 'Your pre-order has been placed successfully! Please review pickup details below.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error placing order: ' . $e->getMessage());
        }
    }

    public function index(Request $request)
    {
        $query = Order::where('customer_id', Auth::id())
            ->with(['farmer', 'farmer.market', 'items.product']);

        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }

        $orders = $query->latest()->paginate(10)->withQueryString();

        return view('orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::where('customer_id', Auth::id())
            ->with(['farmer', 'farmer.market', 'items.product', 'reviews'])
            ->findOrFail($id);

        return view('orders.show', compact('order'));
    }

    public function cancel(Request $request, $id)
    {
        $order = Order::where('customer_id', Auth::id())->findOrFail($id);

        if (!$order->canBeModifiedOrCancelled()) {
            return back()->with('error', 'This order cannot be cancelled as it is past the farmer cutoff time or already completed.');
        }

        $request->validate([
            'cancellation_reason' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            // Restore inventory
            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->increment('stock_quantity', $item->quantity);
                }
            }

            $order->update([
                'order_status' => 'cancelled',
                'cancellation_reason' => $request->cancellation_reason ?? 'Cancelled by customer',
                'cancelled_by' => 'customer',
            ]);

            // Notify farmer
            Announcement::create([
                'user_id' => $order->farmer_id,
                'title' => "Order #{$order->order_number} Cancelled",
                'message' => "Customer cancelled order #{$order->order_number}. Inventory has been automatically restored.",
                'type' => 'order_status',
                'link' => route('farmer.orders'),
            ]);

            DB::commit();
            return back()->with('info', "Order #{$order->order_number} has been cancelled and stock returned.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to cancel order: ' . $e->getMessage());
        }
    }

    public function modify(Request $request, $id)
    {
        $order = Order::where('customer_id', Auth::id())->with('items.product')->findOrFail($id);

        if (!$order->canBeModifiedOrCancelled()) {
            return back()->with('error', 'This order cannot be modified as it is past the farmer cutoff time.');
        }

        $request->validate([
            'quantities' => 'required|array',
            'quantities.*' => 'required|integer|min:1',
            'customer_notes' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $newTotal = 0;
            foreach ($order->items as $item) {
                $newQty = $request->quantities[$item->id] ?? $item->quantity;
                $diff = $newQty - $item->quantity;

                if ($diff > 0) {
                    if ($item->product->stock_quantity < $diff) {
                        throw new \Exception("Cannot increase {$item->product_name} by {$diff}. Only {$item->product->stock_quantity} available.");
                    }
                    $item->product->decrement('stock_quantity', $diff);
                } elseif ($diff < 0) {
                    $item->product->increment('stock_quantity', abs($diff));
                }

                $subtotal = $item->price * $newQty;
                $item->update([
                    'quantity' => $newQty,
                    'subtotal' => $subtotal,
                ]);

                $newTotal += $subtotal;
            }

            $order->update([
                'total_amount' => $newTotal,
                'customer_notes' => $request->customer_notes,
            ]);

            DB::commit();
            return back()->with('success', 'Order updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update order: ' . $e->getMessage());
        }
    }

    public function reorder($id)
    {
        $order = Order::where('customer_id', Auth::id())->with('items.product.farmer.market')->findOrFail($id);
        $cart = session()->get('cart', []);
        $addedCount = 0;

        foreach ($order->items as $item) {
            $product = $item->product;
            if ($product && $product->is_available && $product->stock_quantity > 0) {
                $qty = min($item->quantity, $product->stock_quantity);
                $cart[$product->id] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => (float)$product->price,
                    'unit' => $product->unit,
                    'quantity' => $qty,
                    'stock' => $product->stock_quantity,
                    'image' => $product->image,
                    'farmer_id' => $product->farmer_id,
                    'farmer_name' => $product->farmer->name,
                    'stall_name' => $product->farmer->stall_name ?? $product->farmer->name,
                    'operating_days' => $product->farmer->operating_days ?? 'Weekend',
                    'pickup_windows' => $product->farmer->pickup_windows ?? '08:00 AM - 01:00 PM',
                    'market_name' => $product->farmer->market ? $product->farmer->market->market_name : 'Local Market',
                ];
                $addedCount++;
            }
        }

        session()->put('cart', $cart);

        if ($addedCount > 0) {
            return redirect()->route('cart.index')->with('success', "{$addedCount} items re-added to your pre-order basket!");
        }

        return back()->with('error', 'Sorry, products from this order are currently out of stock.');
    }
}
