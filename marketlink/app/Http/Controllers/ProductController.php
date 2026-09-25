<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Market;
use App\Models\User;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['farmer', 'farmer.market', 'categoryModel'])
            ->whereHas('farmer', function ($q) {
                $q->where('status', 'active');
            });

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $query->where(function ($q) use ($request) {
                $q->where('category', $request->category)
                  ->orWhere('category_id', $request->category);
            });
        }

        // Market filter
        if ($request->filled('market_id')) {
            $query->whereHas('farmer', function ($q) use ($request) {
                $q->where('market_id', $request->market_id);
            });
        }

        // Farmer filter
        if ($request->filled('farmer_id')) {
            $query->where('farmer_id', $request->farmer_id);
        }

        // Operating day filter
        if ($request->filled('day')) {
            $query->whereHas('farmer', function ($q) use ($request) {
                $q->where('operating_days', 'like', "%{$request->day}%");
            });
        }

        // Price range
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // In-stock only
        if ($request->boolean('in_stock')) {
            $query->where('is_available', true)->where('stock_quantity', '>', 0);
        }

        // Sorting
        $sort = $request->input('sort', 'latest');
        match ($sort) {
            'price_low' => $query->orderBy('price', 'asc'),
            'price_high' => $query->orderBy('price', 'desc'),
            'name_asc' => $query->orderBy('name', 'asc'),
            default => $query->latest(),
        };

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::all();
        $markets = Market::orderBy('market_name')->get();
        $farmers = User::where('role', 'farmer')->where('status', 'active')->orderBy('stall_name')->get();

        return view('products.index', compact('products', 'categories', 'markets', 'farmers'));
    }

    public function show($id)
    {
        $product = Product::with([
            'farmer',
            'farmer.market',
            'categoryModel',
            'reviews' => function ($q) {
                $q->where('is_flagged', false)->with('customer')->latest();
            }
        ])->findOrFail($id);

        $relatedProducts = Product::where('farmer_id', $product->farmer_id)
            ->where('id', '!=', $product->id)
            ->where('is_available', true)
            ->take(4)
            ->get();

        $isFavorite = false;
        if (auth()->check() && auth()->user()->isCustomer()) {
            $isFavorite = auth()->user()->favorites()
                ->where('favoritable_type', 'Product')
                ->where('favoritable_id', $product->id)
                ->exists();
        }

        return view('products.show', compact('product', 'relatedProducts', 'isFavorite'));
    }
}
