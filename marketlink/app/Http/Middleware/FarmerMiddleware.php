<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FarmerMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please log in to your Farmer account.');
        }

        $user = auth()->user();

        if (!$user->isFarmer()) {
            abort(403, 'Unauthorized access: Farmer account required.');
        }

        if ($user->isSuspended()) {
            auth()->logout();
            return redirect()->route('login')->with('error', 'Your farmer account has been suspended by the administrator. Please contact support.');
        }

        return $next($request);
    }
}
