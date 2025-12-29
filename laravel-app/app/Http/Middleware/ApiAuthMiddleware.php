<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiAuthMiddleware
{
    /**
     * Handle an incoming request.
     * 
     * This middleware handles API authentication.
     * Can be extended to support JWT, Sanctum, or other auth methods.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized',
                'data' => null,
                'errors' => ['Authentication required'],
            ], 401);
        }

        return $next($request);
    }
}

