<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Market;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Review;
use App\Models\Announcement;
use App\Models\Report;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminPortalController extends Controller
{
    public function dashboard()
    {
        $metrics = [
            'total_farmers' => User::where('role', 'farmer')->count(),
            'active_farmers' => User::where('role', 'farmer')->where('status', 'active')->count(),
            'pending_farmers' => User::where('role', 'farmer')->where('status', 'pending_approval')->count(),
            'total_customers' => User::where('role', 'customer')->count(),
            'total_markets' => Market::count(),
            'total_orders' => Order::count(),
            'total_revenue' => Order::where('order_status', '!=', 'cancelled')->sum('total_amount'),
            'total_products' => Product::count(),
        ];

        $pendingFarmers = User::where('role', 'farmer')
            ->where('status', 'pending_approval')
            ->latest()
            ->take(5)
            ->get();

        $recentOrders = Order::with(['customer', 'farmer', 'market'])
            ->latest()
            ->take(6)
            ->get();

        $recentUsers = User::latest()->take(6)->get();

        return view('admin.dashboard', compact('metrics', 'pendingFarmers', 'recentOrders', 'recentUsers'));
    }

    // Manage Users (Farmers & Customers)
    public function users(Request $request)
    {
        $query = User::query();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('stall_name', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(15)->withQueryString();

        return view('admin.users', compact('users'));
    }

    public function approveFarmer($id)
    {
        $user = User::where('role', 'farmer')->findOrFail($id);
        $user->update(['status' => 'active']);

        Announcement::create([
            'user_id' => $user->id,
            'title' => 'Your Farmer Account is Approved!',
            'message' => 'Congratulations! The MarketLink administration has approved your stall registration. You can now publish weekly products and receive pre-orders.',
            'type' => 'announcement',
            'link' => route('farmer.products'),
        ]);

        return back()->with('success', "Farmer '{$user->name}' ({$user->stall_name}) has been approved and activated.");
    }

    public function suspendUser($id)
    {
        $user = User::findOrFail($id);
        if ($user->isAdmin()) {
            return back()->with('error', 'Administrator accounts cannot be suspended.');
        }

        $user->update(['status' => 'suspended']);

        return back()->with('info', "User '{$user->name}' has been suspended.");
    }

    public function activateUser($id)
    {
        $user = User::findOrFail($id);
        $user->update(['status' => 'active']);

        return back()->with('success', "User '{$user->name}' has been restored to active status.");
    }

    // Manage Markets
    public function markets()
    {
        $markets = Market::withCount(['farmers', 'orders'])->paginate(10);
        return view('admin.markets', compact('markets'));
    }

    public function storeMarket(Request $request)
    {
        $validated = $request->validate([
            'market_name' => 'required|string|max:150',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'operating_days' => 'required|string',
            'timings' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'description' => 'nullable|string|max:1000',
        ]);

        Market::create($validated);

        return back()->with('success', 'Farmers market added successfully.');
    }

    public function updateMarket(Request $request, $id)
    {
        $market = Market::findOrFail($id);

        $validated = $request->validate([
            'market_name' => 'required|string|max:150',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'operating_days' => 'required|string',
            'timings' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'description' => 'nullable|string|max:1000',
        ]);

        $market->update($validated);

        return back()->with('success', 'Market details updated successfully.');
    }

    public function deleteMarket($id)
    {
        $market = Market::findOrFail($id);
        $market->delete();

        return back()->with('info', 'Market removed from the system.');
    }

    // Content Moderation (Products & Reviews)
    public function moderation(Request $request)
    {
        $products = Product::with(['farmer', 'farmer.market'])->latest()->paginate(10, ['*'], 'products_page');
        $reviews = Review::with(['customer', 'farmer', 'product'])->latest()->paginate(10, ['*'], 'reviews_page');

        return view('admin.moderation', compact('products', 'reviews'));
    }

    public function deleteProduct($id)
    {
        $product = Product::findOrFail($id);
        $productName = $product->name;
        $product->delete();

        return back()->with('info', "Product '{$productName}' was removed by moderation.");
    }

    public function deleteReview($id)
    {
        $review = Review::findOrFail($id);
        $review->delete();

        return back()->with('info', "Review was removed by moderation.");
    }

    // Reports and Analytics
    public function reports(Request $request)
    {
        $period = $request->input('period', 'all');

        $orderQuery = Order::where('order_status', '!=', 'cancelled');
        if ($period === 'month') {
            $orderQuery->where('created_at', '>=', Carbon::now()->subDays(30));
        } elseif ($period === 'week') {
            $orderQuery->where('created_at', '>=', Carbon::now()->subDays(7));
        }

        $totalRevenue = (clone $orderQuery)->sum('total_amount');
        $totalOrders = (clone $orderQuery)->count();

        // Revenue by Market
        $marketRevenue = Market::leftJoin('orders', 'markets.id', '=', 'orders.market_id')
            ->where(function ($q) {
                $q->whereNull('orders.order_status')->orWhere('orders.order_status', '!=', 'cancelled');
            })
            ->select('markets.market_name', 'markets.city', DB::raw('COUNT(orders.id) as total_orders'), DB::raw('COALESCE(SUM(orders.total_amount), 0) as total_sales'))
            ->groupBy('markets.id', 'markets.market_name', 'markets.city')
            ->orderByDesc('total_sales')
            ->get();

        // Most Active Farmers
        $mostActiveFarmers = User::where('role', 'farmer')
            ->leftJoin('orders', 'users.id', '=', 'orders.farmer_id')
            ->where(function ($q) {
                $q->whereNull('orders.order_status')->orWhere('orders.order_status', '!=', 'cancelled');
            })
            ->select('users.name', 'users.stall_name', DB::raw('COUNT(orders.id) as orders_count'), DB::raw('COALESCE(SUM(orders.total_amount), 0) as gross_revenue'))
            ->groupBy('users.id', 'users.name', 'users.stall_name')
            ->orderByDesc('orders_count')
            ->take(10)
            ->get();

        // Saved Reports history
        $savedReports = Report::with('generator')->latest()->paginate(5);

        return view('admin.reports', compact(
            'period',
            'totalRevenue',
            'totalOrders',
            'marketRevenue',
            'mostActiveFarmers',
            'savedReports'
        ));
    }

    public function generateReport(Request $request)
    {
        $reportType = $request->input('report_type', 'MarketLink Platform Performance Summary');

        $marketRevenue = Market::leftJoin('orders', 'markets.id', '=', 'orders.market_id')
            ->where(function ($q) {
                $q->whereNull('orders.order_status')->orWhere('orders.order_status', '!=', 'cancelled');
            })
            ->select('markets.market_name', DB::raw('COUNT(orders.id) as orders'), DB::raw('COALESCE(SUM(orders.total_amount), 0) as revenue'))
            ->groupBy('markets.id', 'markets.market_name')
            ->get();

        $activeFarmers = User::where('role', 'farmer')
            ->leftJoin('orders', 'users.id', '=', 'orders.farmer_id')
            ->where(function ($q) {
                $q->whereNull('orders.order_status')->orWhere('orders.order_status', '!=', 'cancelled');
            })
            ->select('users.stall_name', DB::raw('COUNT(orders.id) as orders'), DB::raw('COALESCE(SUM(orders.total_amount), 0) as sales'))
            ->groupBy('users.id', 'users.stall_name')
            ->orderByDesc('sales')
            ->get();

        $summaryData = [
            'total_revenue' => Order::where('order_status', '!=', 'cancelled')->sum('total_amount'),
            'total_orders' => Order::count(),
            'total_farmers' => User::where('role', 'farmer')->count(),
            'total_customers' => User::where('role', 'customer')->count(),
            'market_breakdown' => $marketRevenue,
            'top_farmers' => $activeFarmers,
        ];

        Report::create([
            'generated_by' => Auth::id(),
            'report_type' => $reportType,
            'parameters' => ['generated_at' => now()->toDateTimeString()],
            'summary_data' => $summaryData,
            'generated_at' => now(),
        ]);

        return back()->with('success', 'Official report generated and logged to system reports.');
    }

    // System Configuration (Categories & Announcements)
    public function configuration()
    {
        $categories = Category::withCount('products')->get();
        $announcements = Announcement::latest()->paginate(10);

        return view('admin.configuration', compact('categories', 'announcements'));
    }

    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:categories',
            'icon' => 'required|string|max:50',
            'description' => 'nullable|string|max:500',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        Category::create($validated);

        return back()->with('success', "Category '{$validated['name']}' created successfully.");
    }

    public function updateCategory(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:categories,name,' . $category->id,
            'icon' => 'required|string|max:50',
            'description' => 'nullable|string|max:500',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $category->update($validated);

        return back()->with('success', 'Category updated successfully.');
    }

    public function deleteCategory($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return back()->with('info', 'Category removed.');
    }

    public function storeAnnouncement(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'message' => 'required|string|max:2000',
            'type' => 'required|in:announcement,info,warning,restock',
            'link' => 'nullable|string|max:255',
            'target_role' => 'nullable|in:all,farmer,customer',
        ]);

        $targetRole = $request->input('target_role', 'all');

        if ($targetRole === 'all') {
            Announcement::create([
                'user_id' => null, // platform-wide
                'title' => $validated['title'],
                'message' => $validated['message'],
                'type' => $validated['type'],
                'link' => $validated['link'] ?? null,
            ]);
        } else {
            $users = User::where('role', $targetRole)->get();
            foreach ($users as $u) {
                Announcement::create([
                    'user_id' => $u->id,
                    'title' => $validated['title'],
                    'message' => $validated['message'],
                    'type' => $validated['type'],
                    'link' => $validated['link'] ?? null,
                ]);
            }
        }

        return back()->with('success', 'Platform-wide announcement published to all users.');
    }
}
