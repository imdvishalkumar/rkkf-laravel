<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $role
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        
        // Check role based on parameter
        switch ($role) {
            case 'admin':
                if ($user->role != 1) {
                    abort(403, 'Unauthorized access.');
                }
                break;
            case 'instructor':
                if ($user->role != 2) {
                    abort(403, 'Unauthorized access.');
                }
                break;
            // Add more role checks as needed
        }

        return $next($request);
    }
}
