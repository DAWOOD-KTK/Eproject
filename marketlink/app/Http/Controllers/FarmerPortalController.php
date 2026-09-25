<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Category;
use App\Models\Market;
use App\Models\Review;
use App\Models\Announcement;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FarmerPortalController extends Controller
{
    public function dashboard()
    {
        $farmer = Auth::user();

        // Farmer Insights
        $totalOrders = Order::where('farmer_id', $farmer->id)->count();
        $pendingOrders = Order::where('farmer_id', $farmer->id)->whereIn('order_status', ['placed', 'accepted'])->count();
        $readyOrders = Order::where('farmer_id', $farmer->id)->where('order_status', 'ready_for_pickup')->count();
        $completedOrders = Order::where('farmer_id', $farmer->id)->where('order_status', 'completed')->count();
        
        $revenueSummary = Order::where('farmer_id', $farmer->id)
            ->where('order_status', '!=', 'cancelled')
            ->sum('total_amount');

        // Recent pre-orders
        $recentOrders = Order::where('farmer_id', $farmer->id)
            ->with(['customer', 'items.product'])
            ->latest()
            ->take(5)
            ->get();

        // Best-selling products
        $bestSellers = OrderItem::select('product_name', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(subtotal) as total_sales'))
            ->whereHas('order', function ($q) use ($farmer) {
                $q->where('farmer_id', $farmer->id)->where('order_status', '!=', 'cancelled');
            })
            ->groupBy('product_name')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        // Products count
        $activeProductsCount = Product::where('farmer_id', $farmer->id)->where('is_available', true)->count();
        $soldOutCount = Product::where('farmer_id', $farmer->id)->where('stock_quantity', '<=', 0)->count();

        return view('farmer.dashboard', compact(
            'farmer',
            'totalOrders',
            'pendingOrders',
            'readyOrders',
            'completedOrders',
            'revenueSummary',
            'recentOrders',
            'bestSellers',
            'activeProductsCount',
            'soldOutCount'
        ));
    }

    public function products(Request $request)
    {
        $farmer = Auth::user();
        $query = Product::where('farmer_id', $farmer->id)->with('categoryModel');

        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        if ($request->filled('status')) {
            if ($request->status === 'available') {
                $query->where('is_available', true)->where('stock_quantity', '>', 0);
            } elseif ($request->status === 'sold_out') {
                $query->where(function ($q) {
                    $q->where('stock_quantity', '<=', 0)->orWhere('is_available', false);
                });
            }
        }

        $products = $query->latest()->paginate(10)->withQueryString();
        $categories = Category::all();

        return view('farmer.products', compact('products', 'categories', 'farmer'));
    }

    public function storeProduct(Request $request)
    {
        $farmer = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0.01',
            'unit' => 'required|string|max:20',
            'stock_quantity' => 'required|integer|min:0',
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_available' => 'nullable|boolean',
            'is_weekly_template' => 'nullable|boolean',
        ]);

        $category = Category::findOrFail($validated['category_id']);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        Product::create([
            'farmer_id' => $farmer->id,
            'category_id' => $category->id,
            'category' => $category->name,
            'name' => $validated['name'],
            'price' => $validated['price'],
            'unit' => $validated['unit'],
            'stock_quantity' => $validated['stock_quantity'],
            'description' => $validated['description'] ?? null,
            'image' => $imagePath,
            'is_available' => $request->has('is_available') ? true : false,
            'is_weekly_template' => $request->has('is_weekly_template') ? true : false,
        ]);

        return back()->with('success', 'Product added successfully to your market catalog.');
    }

    public function updateProduct(Request $request, $id)
    {
        $farmer = Auth::user();
        $product = Product::where('farmer_id', $farmer->id)->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0.01',
            'unit' => 'required|string|max:20',
            'stock_quantity' => 'required|integer|min:0',
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_available' => 'nullable|boolean',
            'is_weekly_template' => 'nullable|boolean',
        ]);

        $category = Category::findOrFail($validated['category_id']);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $product->image = $request->file('image')->store('products', 'public');
        }

        $product->update([
            'category_id' => $category->id,
            'category' => $category->name,
            'name' => $validated['name'],
            'price' => $validated['price'],
            'unit' => $validated['unit'],
            'stock_quantity' => $validated['stock_quantity'],
            'description' => $validated['description'] ?? null,
            'is_available' => $request->has('is_available') ? true : false,
            'is_weekly_template' => $request->has('is_weekly_template') ? true : false,
        ]);

        return back()->with('success', 'Product updated successfully.');
    }

    public function deleteProduct($id)
    {
        $farmer = Auth::user();
        $product = Product::where('farmer_id', $farmer->id)->findOrFail($id);

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return back()->with('info', 'Product removed from your listings.');
    }

    public function toggleAvailability($id)
    {
        $farmer = Auth::user();
        $product = Product::where('farmer_id', $farmer->id)->findOrFail($id);

        $product->is_available = !$product->is_available;
        $product->save();

        $statusText = $product->is_available ? 'Available' : 'Marked as Sold Out / Unavailable';
        return back()->with('success', "{$product->name} status changed to {$statusText}.");
    }

    // Weekly Recurring Stock Template
    public function applyWeeklyTemplate()
    {
        $farmer = Auth::user();
        $templateProducts = Product::where('farmer_id', $farmer->id)
            ->where('is_weekly_template', true)
            ->get();

        if ($templateProducts->isEmpty()) {
            return back()->with('error', 'No products are marked as part of your weekly recurring stock template.');
        }

        foreach ($templateProducts as $product) {
            // Restore product to available with standard initial weekly allocation
            $product->update([
                'is_available' => true,
                'stock_quantity' => max($product->stock_quantity, 30),
            ]);
        }

        return back()->with('success', 'Weekly stock template applied! ' . $templateProducts->count() . ' items replenished for the upcoming market week.');
    }

    // Manage Pre-orders
    public function orders(Request $request)
    {
        $farmer = Auth::user();
        $query = Order::where('farmer_id', $farmer->id)
            ->with(['customer', 'items.product', 'market']);

        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }

        if ($request->filled('date')) {
            $query->whereDate('pickup_date', $request->date);
        }

        $orders = $query->latest()->paginate(12)->withQueryString();

        return view('farmer.orders', compact('orders', 'farmer'));
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $farmer = Auth::user();
        $order = Order::where('farmer_id', $farmer->id)->findOrFail($id);

        $request->validate([
            'order_status' => 'required|in:accepted,declined,ready_for_pickup,completed',
            'farmer_notes' => 'nullable|string|max:500',
        ]);

        $status = $request->order_status;

        if ($status === 'declined') {
            // Restore inventory
            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->increment('stock_quantity', $item->quantity);
                }
            }
            $order->update([
                'order_status' => 'cancelled',
                'farmer_notes' => $request->farmer_notes ?? 'Declined by farmer due to stock unavailability.',
                'cancelled_by' => 'farmer',
            ]);

            Announcement::create([
                'user_id' => $order->customer_id,
                'title' => "Order #{$order->order_number} Declined",
                'message' => "{$farmer->stall_name} was unable to fulfill order #{$order->order_number}: " . ($request->farmer_notes ?? 'Out of stock.'),
                'type' => 'order_status',
                'link' => route('customer.orders.show', $order->id),
            ]);

            return back()->with('info', "Order #{$order->order_number} declined and stock restored.");
        }

        $order->update([
            'order_status' => $status,
            'farmer_notes' => $request->farmer_notes,
        ]);

        // Customer in-app alerts based on status
        if ($status === 'ready_for_pickup') {
            Announcement::create([
                'user_id' => $order->customer_id,
                'title' => "Your Order #{$order->order_number} is Ready for Pickup!",
                'message' => "Your pre-order has been packed by {$farmer->stall_name} and is waiting for you at {$farmer->stall_name}, {$order->pickup_time_slot}.",
                'type' => 'order_status',
                'link' => route('customer.orders.show', $order->id),
            ]);
        } elseif ($status === 'accepted') {
            Announcement::create([
                'user_id' => $order->customer_id,
                'title' => "Order #{$order->order_number} Accepted by Farmer",
                'message' => "{$farmer->stall_name} has accepted your pre-order for {$order->pickup_date->format('M d, Y')}.",
                'type' => 'order_status',
                'link' => route('customer.orders.show', $order->id),
            ]);
        } elseif ($status === 'completed') {
            Announcement::create([
                'user_id' => $order->customer_id,
                'title' => "Order #{$order->order_number} Completed! Leave a Review",
                'message' => "Thank you for picking up your fresh produce from {$farmer->stall_name}! Please rate and review your experience.",
                'type' => 'order_status',
                'link' => route('customer.orders.show', $order->id),
            ]);
        }

        return back()->with('success', "Order #{$order->order_number} marked as {$order->status_label}.");
    }

    // Profile & Stall Settings
    public function settings()
    {
        $farmer = Auth::user();
        $markets = Market::orderBy('market_name')->get();
        return view('farmer.settings', compact('farmer', 'markets'));
    }

    public function updateSettings(Request $request)
    {
        $farmer = Auth::user();

        $validated = $request->validate([
            'stall_name' => 'required|string|max:150',
            'contact_person' => 'required|string|max:100',
            'phone' => 'required|string|max:30',
            'address' => 'required|string',
            'market_id' => 'nullable|exists:markets,id',
            'operating_days' => 'required|string',
            'pickup_windows' => 'required|string',
            'cutoff_hours' => 'required|integer|min:1|max:72',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'bio' => 'nullable|string|max:1000',
        ]);

        $farmer->update($validated);

        return back()->with('success', 'Stall settings and pickup configuration updated successfully.');
    }

    // Order History & Analytics
    public function analytics()
    {
        $farmer = Auth::user();

        $totalSales = Order::where('farmer_id', $farmer->id)->where('order_status', '!=', 'cancelled')->sum('total_amount');
        $totalOrders = Order::where('farmer_id', $farmer->id)->count();
        $completedOrders = Order::where('farmer_id', $farmer->id)->where('order_status', 'completed')->count();
        $cancelledOrders = Order::where('farmer_id', $farmer->id)->where('order_status', 'cancelled')->count();

        $bestSellers = OrderItem::select('product_name', DB::raw('SUM(quantity) as units_sold'), DB::raw('SUM(subtotal) as gross_revenue'))
            ->whereHas('order', function ($q) use ($farmer) {
                $q->where('farmer_id', $farmer->id)->where('order_status', '!=', 'cancelled');
            })
            ->groupBy('product_name')
            ->orderByDesc('units_sold')
            ->get();

        // Monthly sales summary
        $monthlySales = Order::select(
                DB::raw('DATE_FORMAT(pickup_date, "%Y-%m") as month'),
                DB::raw('COUNT(*) as order_count'),
                DB::raw('SUM(total_amount) as revenue')
            )
            ->where('farmer_id', $farmer->id)
            ->where('order_status', '!=', 'cancelled')
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->take(6)
            ->get();

        return view('farmer.analytics', compact('farmer', 'totalSales', 'totalOrders', 'completedOrders', 'cancelledOrders', 'bestSellers', 'monthlySales'));
    }

    // Customer Reviews & Replies
    public function reviews()
    {
        $farmer = Auth::user();
        $reviews = Review::where('farmer_id', $farmer->id)
            ->with(['customer', 'product', 'order'])
            ->latest()
            ->paginate(10);

        return view('farmer.reviews', compact('reviews', 'farmer'));
    }
}
