<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */

    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = Auth::user();

        // Debugging
        \Log::info('User role:', ['role' => $user->role]);
        \Log::info('Allowed roles:', ['roles' => $roles]);

        if (!$user || !in_array($user->role, $roles)) {
            // Redirect or return an error response if the user does not have the required role
            return redirect()->route('dashboard')->withErrors('You do not have permission to access this page.');
        }

        return $next($request);
    }
}
