<?php

namespace App\Http\Controllers;

use App\Models\Market;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Review;
use App\Models\Announcement;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $markets = Market::withCount('farmers')->take(4)->get();
        $categories = Category::withCount('products')->get();
        
        $featuredProducts = Product::with(['farmer', 'farmer.market', 'categoryModel'])
            ->where('is_available', true)
            ->where('stock_quantity', '>', 0)
            ->whereHas('farmer', function ($q) {
                $q->where('status', 'active');
            })
            ->latest()
            ->take(8)
            ->get();

        $featuredFarmers = User::where('role', 'farmer')
            ->where('status', 'active')
            ->with(['market', 'farmerProducts' => function ($q) {
                $q->where('is_available', true)->take(3);
            }])
            ->take(4)
            ->get();

        $reviews = Review::with(['customer', 'product', 'farmer'])
            ->where('is_flagged', false)
            ->latest()
            ->take(4)
            ->get();

        $announcements = Announcement::whereNull('user_id')
            ->latest()
            ->take(2)
            ->get();

        $stats = [
            'markets' => Market::count(),
            'farmers' => User::where('role', 'farmer')->where('status', 'active')->count(),
            'products' => Product::where('is_available', true)->count(),
            'customers' => User::where('role', 'customer')->count(),
        ];

        return view('home.index', compact(
            'markets',
            'categories',
            'featuredProducts',
            'featuredFarmers',
            'reviews',
            'announcements',
            'stats'
        ));
    }

    public function about()
    {
        $stats = [
            'markets' => Market::count(),
            'farmers' => User::where('role', 'farmer')->where('status', 'active')->count(),
            'products' => Product::where('is_available', true)->count(),
        ];
        return view('home.about', compact('stats'));
    }

    public function contact()
    {
        return view('home.contact');
    }

    public function contactSubmit(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150',
            'subject' => 'required|string|max:150',
            'message' => 'required|string|max:2000',
        ]);

        return back()->with('success', 'Thank you for contacting MarketLink! Our support team will get back to you shortly.');
    }
}
