<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'nullable|exists:orders,id',
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        $product = Product::findOrFail($request->product_id);

        // Verify if user bought this product or if order is completed
        if ($request->filled('order_id')) {
            $order = Order::where('customer_id', Auth::id())
                ->where('id', $request->order_id)
                ->where('order_status', 'completed')
                ->first();

            if (!$order) {
                return back()->with('error', 'Reviews can be submitted once an order is marked completed.');
            }
        }

        Review::create([
            'order_id' => $request->order_id,
            'product_id' => $product->id,
            'farmer_id' => $product->farmer_id,
            'customer_id' => Auth::id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Thank you! Your review and rating have been posted.');
    }

    public function reply(Request $request, $id)
    {
        $request->validate([
            'farmer_reply' => 'required|string|max:1000',
        ]);

        $review = Review::where('farmer_id', Auth::id())->findOrFail($id);

        $review->update([
            'farmer_reply' => $request->farmer_reply,
            'farmer_replied_at' => now(),
        ]);

        return back()->with('success', 'Your response has been published.');
    }
}
