<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class EmailVerificationController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function show(Request $request): View
    {
        return view('auth.verify-email');
    }

    /**
     * Mark the authenticated user's email address as verified.
     */
    public function verify(Request $request): RedirectResponse
    {
        $user = User::where('id', $request->route('id'))
                   ->where('email_verification_token', $request->route('token'))
                   ->first();

        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'Invalid verification link.');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard'))
                ->with('success', 'Email already verified.');
        }

        // Check if verification token is expired
        if ($user->isVerificationTokenExpired()) {
            return redirect()->route('verification.notice')
                ->with('error', 'Verification link has expired. Please request a new one.');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
            
            // Log out any currently authenticated user to ensure clean login flow
            Auth::logout();
            
            // Create success message based on user role
            $message = 'Email verified successfully! You can now log in to your account.';
            
            // Additional message for nutritionists
            if ($user->isNutritionist()) {
                $message = 'Email verified successfully! Your account is pending admin approval. You will be able to log in once approved.';
            }
            
            // Redirect to login page instead of auto-login
            return redirect()->route('login')
                ->with('success', $message);
        }

        return redirect()->route('login')
            ->with('error', 'Email verification failed.');
    }

    /**
     * Send a new email verification notification.
     */
    public function resend(Request $request): RedirectResponse
    {
        $user = $request->user();
        
        if ($user->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard'));
        }

        // Check if user has exceeded verification attempts
        if ($user->hasExceededVerificationAttempts()) {
            return back()->with('error', 'Too many verification attempts. Please contact support.');
        }

        // Check rate limiting
        if (!$user->canRequestVerificationEmail()) {
            return back()->with('error', 'Please wait before requesting another verification email.');
        }

        $user->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }
}
