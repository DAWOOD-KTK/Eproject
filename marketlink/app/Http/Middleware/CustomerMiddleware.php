<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomerMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please log in to continue.');
        }

        $user = auth()->user();

        if ($user->isSuspended()) {
            auth()->logout();
            return redirect()->route('login')->with('error', 'Your customer account has been deactivated. Please contact support.');
        }

        return $next($request);
    }
}
