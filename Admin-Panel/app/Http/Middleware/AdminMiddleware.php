<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // For now, allow all authenticated users as admin
        // Customize this logic to check for admin role/flag
        if (!Auth::check()) {
            return redirect('/login');
        }
        return $next($request);
    }
} 