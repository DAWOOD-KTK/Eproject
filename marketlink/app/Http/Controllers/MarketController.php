<?php

namespace App\Http\Controllers;

use App\Models\Market;
use App\Models\User;
use Illuminate\Http\Request;

class MarketController extends Controller
{
    public function index(Request $request)
    {
        $query = Market::withCount('farmers');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('market_name', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
            });
        }

        if ($request->filled('day')) {
            $query->where('operating_days', 'like', "%{$request->day}%");
        }

        $markets = $query->paginate(9)->withQueryString();

        // Prepare map markers payload for interactive map
        $allMarketsForMap = Market::all(['id', 'market_name', 'address', 'operating_days', 'timings', 'latitude', 'longitude']);

        return view('markets.index', compact('markets', 'allMarketsForMap'));
    }

    public function show($id)
    {
        $market = Market::with(['farmers' => function ($q) {
            $q->where('status', 'active')->with(['farmerProducts' => function ($pq) {
                $pq->where('is_available', true)->take(4);
            }]);
        }])->findOrFail($id);

        return view('markets.show', compact('market'));
    }

    public function map()
    {
        $markets = Market::all(['id', 'market_name', 'address', 'operating_days', 'timings', 'latitude', 'longitude']);
        $farmers = User::where('role', 'farmer')
            ->where('status', 'active')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->with('market')
            ->get(['id', 'name', 'stall_name', 'address', 'operating_days', 'pickup_windows', 'latitude', 'longitude', 'market_id']);

        return view('markets.map', compact('markets', 'farmers'));
    }
}
