<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Normalize roles: if a single comma-separated string was passed, explode it
        if (count($roles) === 1 && is_string($roles[0]) && strpos($roles[0], ',') !== false) {
            $roles = array_map('trim', explode(',', $roles[0]));
        }

        if (in_array($user->role, $roles, true)) {
            return $next($request);
        }

        abort(403, 'Unauthorized access.');
    }
}
