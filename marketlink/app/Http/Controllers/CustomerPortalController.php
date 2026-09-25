<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Favorite;
use App\Models\Announcement;
use App\Models\Market;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerPortalController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        $activeOrders = Order::where('customer_id', $user->id)
            ->whereIn('order_status', ['placed', 'accepted', 'ready_for_pickup'])
            ->with(['farmer', 'farmer.market', 'items.product'])
            ->latest()
            ->take(5)
            ->get();

        $pastOrders = Order::where('customer_id', $user->id)
            ->whereIn('order_status', ['completed', 'cancelled'])
            ->with(['farmer', 'farmer.market', 'items.product'])
            ->latest()
            ->take(5)
            ->get();

        $favoritesCount = Favorite::where('customer_id', $user->id)->count();

        $notifications = Announcement::where(function ($q) use ($user) {
            $q->where('user_id', $user->id)->orWhereNull('user_id');
        })
        ->latest()
        ->take(5)
        ->get();

        $stats = [
            'total_orders' => Order::where('customer_id', $user->id)->count(),
            'active_orders' => $activeOrders->count(),
            'completed_orders' => Order::where('customer_id', $user->id)->where('order_status', 'completed')->count(),
            'favorites_count' => $favoritesCount,
        ];

        return view('customer.dashboard', compact('activeOrders', 'pastOrders', 'notifications', 'stats'));
    }

    public function notifications()
    {
        $user = Auth::user();

        $notifications = Announcement::where(function ($q) use ($user) {
            $q->where('user_id', $user->id)->orWhereNull('user_id');
        })
        ->latest()
        ->paginate(15);

        // Mark unread as read
        Announcement::where('user_id', $user->id)->where('is_read', false)->update(['is_read' => true]);

        return view('customer.notifications', compact('notifications'));
    }

    public function profile()
    {
        $user = Auth::user();
        $markets = Market::orderBy('market_name')->get();
        return view('customer.profile', compact('user', 'markets'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'phone' => 'required|string|max:30',
            'address' => 'required|string|max:500',
            'preferred_market_id' => 'nullable|exists:markets,id',
        ]);

        $user->update($validated);

        return back()->with('success', 'Profile updated successfully.');
    }
}
