<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RequireRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Unauthorized',
                    'message' => 'Authentication required'
                ], 401);
            }
            return redirect()->route('login');
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        // Check if user has one of the required roles
        if (!in_array($user->role, $roles)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Forbidden',
                    'message' => 'Insufficient permissions. Required roles: ' . implode(', ', $roles),
                    'user_role' => $user->role
                ], 403);
            }
            abort(403, 'Access denied. Required roles: ' . implode(', ', $roles));
        }

        // Check if nutritionist is approved (if user is nutritionist)
        if ($user->role === 'nutritionist' && $user->status !== 'approved') {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Account Pending',
                    'message' => 'Your nutritionist account is pending approval',
                    'status' => $user->status
                ], 403);
            }
            abort(403, 'Your nutritionist account is pending approval');
        }

        return $next($request);
    }
}
