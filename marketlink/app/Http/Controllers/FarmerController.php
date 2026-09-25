<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Market;
use Illuminate\Http\Request;

class FarmerController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'farmer')
            ->where('status', 'active')
            ->with(['market', 'farmerProducts' => function ($q) {
                $q->where('is_available', true);
            }]);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('stall_name', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('bio', 'like', "%{$search}%");
            });
        }

        if ($request->filled('market_id')) {
            $query->where('market_id', $request->market_id);
        }

        if ($request->filled('day')) {
            $query->where('operating_days', 'like', "%{$request->day}%");
        }

        $farmers = $query->paginate(9)->withQueryString();
        $markets = Market::orderBy('market_name')->get();

        return view('farmers.index', compact('farmers', 'markets'));
    }

    public function show($id)
    {
        $farmer = User::where('role', 'farmer')
            ->where('status', 'active')
            ->with(['market', 'farmerProducts' => function ($q) {
                $q->where('is_available', true);
            }, 'farmerReviews' => function ($q) {
                $q->where('is_flagged', false)->with('customer')->latest();
            }])
            ->findOrFail($id);

        $isFavorite = false;
        if (auth()->check() && auth()->user()->isCustomer()) {
            $isFavorite = auth()->user()->favorites()
                ->where('favoritable_type', 'Farmer')
                ->where('favoritable_id', $farmer->id)
                ->exists();
        }

        return view('farmers.show', compact('farmer', 'isFavorite'));
    }
}
