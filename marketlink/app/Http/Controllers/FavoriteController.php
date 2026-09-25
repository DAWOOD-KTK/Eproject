<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $favoriteFarmers = Favorite::where('customer_id', $user->id)
            ->where('favoritable_type', 'Farmer')
            ->pluck('favoritable_id');

        $farmers = User::whereIn('id', $favoriteFarmers)
            ->where('role', 'farmer')
            ->with(['market', 'farmerProducts' => function ($q) {
                $q->where('is_available', true);
            }])
            ->get();

        $favoriteProducts = Favorite::where('customer_id', $user->id)
            ->where('favoritable_type', 'Product')
            ->pluck('favoritable_id');

        $products = Product::whereIn('id', $favoriteProducts)
            ->with(['farmer', 'farmer.market', 'categoryModel'])
            ->get();

        return view('customer.favorites', compact('farmers', 'products'));
    }

    public function toggle(Request $request)
    {
        $request->validate([
            'type' => 'required|in:Farmer,Product',
            'id' => 'required|integer',
        ]);

        $customerId = Auth::id();
        $type = $request->type;
        $id = $request->id;

        $favorite = Favorite::where('customer_id', $customerId)
            ->where('favoritable_type', $type)
            ->where('favoritable_id', $id)
            ->first();

        if ($favorite) {
            $favorite->delete();
            $status = 'removed';
            $msg = "Removed from your favorites.";
        } else {
            Favorite::create([
                'customer_id' => $customerId,
                'favoritable_type' => $type,
                'favoritable_id' => $id,
            ]);
            $status = 'added';
            $msg = "Added to your favorites!";
        }

        if ($request->wantsJson()) {
            return response()->json([
                'status' => $status,
                'message' => $msg,
            ]);
        }

        return back()->with('success', $msg);
    }
}
