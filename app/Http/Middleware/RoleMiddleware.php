<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role)
    {
        if (! auth()->check() || auth()->user()->role !== $role) {
            if (auth()->check()) {
                abort(403, 'Access denied.');
            }

            return redirect()->route('login');
        }

        return $next($request);
    }
}
