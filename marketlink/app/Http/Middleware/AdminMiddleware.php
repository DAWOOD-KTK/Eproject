<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please log in as an administrator.');
        }

        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access: Administrator rights required.');
        }

        return $next($request);
    }
}
