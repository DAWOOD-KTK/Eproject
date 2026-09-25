<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $groupedCart = [];
        $totalAmount = 0;

        foreach ($cart as $id => $item) {
            $farmerId = $item['farmer_id'];
            if (!isset($groupedCart[$farmerId])) {
                $groupedCart[$farmerId] = [
                    'farmer_name' => $item['farmer_name'],
                    'stall_name' => $item['stall_name'],
                    'operating_days' => $item['operating_days'],
                    'pickup_windows' => $item['pickup_windows'],
                    'market_name' => $item['market_name'],
                    'items' => [],
                    'subtotal' => 0,
                ];
            }
            $itemSubtotal = $item['price'] * $item['quantity'];
            $groupedCart[$farmerId]['items'][$id] = $item;
            $groupedCart[$farmerId]['subtotal'] += $itemSubtotal;
            $totalAmount += $itemSubtotal;
        }

        return view('cart.index', compact('groupedCart', 'totalAmount'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::with(['farmer', 'farmer.market'])->findOrFail($request->product_id);

        if (!$product->is_available || $product->stock_quantity <= 0) {
            return back()->with('error', 'Sorry, this product is currently sold out.');
        }

        $cart = session()->get('cart', []);
        $quantityToAdd = $request->quantity;

        if (isset($cart[$product->id])) {
            $newQuantity = $cart[$product->id]['quantity'] + $quantityToAdd;
            if ($newQuantity > $product->stock_quantity) {
                return back()->with('error', "Only {$product->stock_quantity} {$product->unit} available in stock.");
            }
            $cart[$product->id]['quantity'] = $newQuantity;
        } else {
            if ($quantityToAdd > $product->stock_quantity) {
                return back()->with('error', "Only {$product->stock_quantity} {$product->unit} available in stock.");
            }
            $cart[$product->id] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => (float)$product->price,
                'unit' => $product->unit,
                'quantity' => $quantityToAdd,
                'stock' => $product->stock_quantity,
                'image' => $product->image,
                'farmer_id' => $product->farmer_id,
                'farmer_name' => $product->farmer->name,
                'stall_name' => $product->farmer->stall_name ?? $product->farmer->name,
                'operating_days' => $product->farmer->operating_days ?? 'Weekend',
                'pickup_windows' => $product->farmer->pickup_windows ?? '08:00 AM - 01:00 PM',
                'market_name' => $product->farmer->market ? $product->farmer->market->market_name : 'Local Market',
            ];
        }

        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', "{$product->name} added to your pre-order basket!");
    }

    public function update(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = session()->get('cart', []);
        $productId = $request->product_id;

        if (isset($cart[$productId])) {
            $product = Product::findOrFail($productId);
            if ($request->quantity > $product->stock_quantity) {
                return back()->with('error', "Only {$product->stock_quantity} {$product->unit} available in stock.");
            }
            $cart[$productId]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
            return back()->with('success', 'Basket updated successfully.');
        }

        return back()->with('error', 'Item not found in basket.');
    }

    public function remove(Request $request)
    {
        $cart = session()->get('cart', []);
        $productId = $request->product_id;

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->put('cart', $cart);
            return back()->with('info', 'Item removed from basket.');
        }

        return back()->with('error', 'Item not found in basket.');
    }

    public function clear()
    {
        session()->forget('cart');
        return redirect()->route('cart.index')->with('info', 'Your pre-order basket has been emptied.');
    }
}
