<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Update last login timestamp
        $user = Auth::user();
        $user->updateLastLogin();

        // Check user status and redirect accordingly
        if (!$user->isActive()) {
            Auth::logout();
            return redirect()->route('login')
                ->withErrors(['email' => 'Your account has been deactivated. Please contact support.']);
        }

        if (!$user->hasVerifiedEmail()) {
            return redirect()->route('verification.notice')
                ->with('error', 'Please verify your email address before proceeding.');
        }

        if ($user->isPending()) {
            return redirect()->route('dashboard')
                ->with('warning', 'Your account is pending approval. You have limited access.');
        }

        if ($user->status === 'suspended') {
            Auth::logout();
            return redirect()->route('login')
                ->withErrors(['email' => 'Your account has been suspended. Please contact support.']);
        }

        // Redirect based on role
        $redirectRoute = match($user->role) {
            'admin' => 'admin.dashboard',
            'nutritionist' => 'nutritionist.dashboard',
            'parent_guardian' => 'dashboard',
            default => 'dashboard',
        };

        return redirect()->intended(route($redirectRoute, absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
