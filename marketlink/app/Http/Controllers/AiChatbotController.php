<?php

namespace App\Http\Controllers;

use App\Models\Market;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;

class AiChatbotController extends Controller
{
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:500',
        ]);

        $query = strtolower(trim($request->message));
        $reply = '';
        $suggestions = [];

        // 1. Market Timings / Operating Days Query
        if (str_contains($query, 'timing') || str_contains($query, 'time') || str_contains($query, 'hour') || str_contains($query, 'open') || str_contains($query, 'when')) {
            $markets = Market::all();
            $reply = "Here are the operating days and timings for our local farmers markets:<br><ul class='mt-2 mb-0 ps-3'>";
            foreach ($markets as $m) {
                $reply .= "<li><strong>{$m->market_name}:</strong> {$m->operating_days} ({$m->timings}) - <em>{$m->city}</em></li>";
            }
            $reply .= "</ul><p class='mt-2 mb-0'>Would you like to browse products from any specific market?</p>";
            $suggestions = ['Show available markets', 'Find organic vegetables', 'How does pickup work?'];
        }
        // 2. Pickup / Pre-order / Payment explanation
        elseif (str_contains($query, 'pickup') || str_contains($query, 'pre-order') || str_contains($query, 'preorder') || str_contains($query, 'pay') || str_contains($query, 'order') || str_contains($query, 'how')) {
            $reply = "<strong>How Pre-Ordering on MarketLink Works:</strong><br>" .
                     "1. <strong>Browse Stock:</strong> Explore fresh weekly stock listed directly by local farmers.<br>" .
                     "2. <strong>Reserve for Pickup:</strong> Add items to your basket and choose a pickup date & time window.<br>" .
                     "3. <strong>Collect & Pay in Person:</strong> Head to the farmer's market stall on market day to collect your freshly packed produce and settle payment in person (cash/direct).<br>" .
                     "4. <strong>Modify/Cancel:</strong> You can modify or cancel any order before the farmer's cutoff window!";
            $suggestions = ['View all products', 'Browse farmers stalls', 'What are market timings?'];
        }
        // 3. Product Search Query
        else {
            // Check for specific products matching keywords
            $matchedProducts = Product::where('is_available', true)
                ->where(function ($q) use ($query) {
                    $terms = explode(' ', $query);
                    foreach ($terms as $term) {
                        if (strlen($term) > 2 && !in_array($term, ['the', 'and', 'for', 'who', 'has', 'any', 'some', 'where', 'find', 'sell', 'sells', 'want', 'buy', 'looking'])) {
                            $q->orWhere('name', 'like', "%{$term}%")
                              ->orWhere('description', 'like', "%{$term}%")
                              ->orWhere('category', 'like', "%{$term}%");
                        }
                    }
                })
                ->with(['farmer', 'farmer.market'])
                ->take(4)
                ->get();

            if ($matchedProducts->isNotEmpty()) {
                $reply = "I found <strong>" . $matchedProducts->count() . " fresh item(s)</strong> matching your request:<br><ul class='mt-2 mb-0 ps-3'>";
                foreach ($matchedProducts as $p) {
                    $farmerName = $p->farmer->stall_name ?? $p->farmer->name;
                    $marketName = $p->farmer->market ? $p->farmer->market->market_name : 'Local Market';
                    $url = route('products.show', $p->id);
                    $reply .= "<li><a href='{$url}' class='text-success fw-bold text-decoration-none'>{$p->name}</a> - <strong>$" . number_format($p->price, 2) . " / {$p->unit}</strong> ({$p->stock_quantity} in stock at {$farmerName}, {$marketName})</li>";
                }
                $reply .= "</ul><p class='mt-2 mb-0'>Click any product above to view details and reserve for pickup!</p>";
                $suggestions = ['Show all fresh products', 'View markets map', 'Farmer contact info'];
            }
            // 4. Farmer Search Query
            elseif (str_contains($query, 'farmer') || str_contains($query, 'stall') || str_contains($query, 'who')) {
                $farmers = User::where('role', 'farmer')->where('status', 'active')->with('market')->take(4)->get();
                $reply = "Here are our registered local farmers and stalls:<br><ul class='mt-2 mb-0 ps-3'>";
                foreach ($farmers as $f) {
                    $marketName = $f->market ? $f->market->market_name : 'Downtown';
                    $url = route('farmers.show', $f->id);
                    $reply .= "<li><a href='{$url}' class='text-success fw-bold text-decoration-none'>{$f->stall_name}</a> - ({$marketName}) | Days: {$f->operating_days}</li>";
                }
                $reply .= "</ul>";
                $suggestions = ['View all farmers', 'Check market timings', 'Search products'];
            }
            // 5. General Fallback
            else {
                $reply = "Hello! I am your <strong>MarketLink Assistant</strong> 🌾.<br> I can help you find fresh produce, check market timings, locate farmer stalls, and explain how pre-orders work. Try asking:<br>" .
                         "• <em>'What time does Central Market open?'</em><br>" .
                         "• <em>'Who has organic tomatoes or honey?'</em><br>" .
                         "• <em>'How do pre-orders and pickup work?'</em>";
                $suggestions = ['Market timings', 'Fresh heirloom tomatoes', 'Pasture eggs', 'Browse all markets'];
            }
        }

        return response()->json([
            'reply' => $reply,
            'suggestions' => $suggestions,
        ]);
    }
}
