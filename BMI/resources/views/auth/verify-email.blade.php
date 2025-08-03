<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email - LakasApp</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <link rel="stylesheet" href="{{ asset('css/verify-email.css') }}">
</head>
<body>
    <div class="verification-container">
        <!-- Success/Error Status Messages -->
        @if (session('status') == 'verification-link-sent')
            <div class="status-message status-success">
                ✅ A new verification link has been sent to your email address!
            </div>
        @elseif (session('status'))
            <div class="status-message status-success">
                {{ session('status') }}
            </div>
        @endif

        @if (session('error'))
            <div class="status-message status-error">
                ❌ {{ session('error') }}
            </div>
        @endif

        <!-- Verification Icon -->
        <div class="verification-icon">
            📧
        </div>

        <!-- Title -->
        <h1 class="verification-title">Check Your Email!</h1>

        <!-- Message -->
        <p class="verification-message">
            Thank you for registering with <strong>LakasApp</strong>! We've sent a verification link to your email address to ensure your account security.
        </p>

        <!-- Email Display -->
        @auth
            <div class="email-display">
                {{ auth()->user()->email }}
            </div>
        @endauth

        <!-- Verification Steps -->
        <div class="verification-steps">
            <h4>📋 What to do next:</h4>
            <ol>
                <li><strong>Check your inbox</strong> for an email from LakasApp</li>
                <li><strong>Click the verification link</strong> in the email</li>
                <li><strong>Return here</strong> to access your account</li>
                <li>If you don't see the email, <strong>check your spam folder</strong></li>
            </ol>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <!-- Resend Verification Email Button -->
            @auth
                <form method="POST" action="{{ route('verification.send') }}" class="inline-form">
                    @csrf
                    <button type="submit" class="btn btn-primary" id="resend-btn">
                        🔄 Resend Verification Email
                    </button>
                </form>
            @endauth

            <!-- Return to Login Button -->
            <a href="{{ route('login') }}" class="btn btn-secondary">
                ← Return to Login
            </a>
        </div>

        <!-- Resend Info -->
        <p class="resend-info">
            Didn't receive the email? You can request a new verification email using the button above.
        </p>

        <!-- Logout Option -->
        @auth
            <div class="logout-section">
                <form method="POST" action="{{ route('logout') }}" class="inline-form">
                    @csrf
                    <button type="submit" class="btn btn-secondary btn-small">
                        🚪 Logout
                    </button>
                </form>
            </div>
        @endauth
    </div>

    <script>
        // Show loading state when resending email
        document.addEventListener('DOMContentLoaded', function() {
            const resendForm = document.querySelector('form[action*="verification.send"]');
            if (resendForm) {
                resendForm.addEventListener('submit', function(e) {
                    const button = this.querySelector('#resend-btn');
                    if (button) {
                        const originalText = button.innerHTML;
                        button.innerHTML = '⏳ Sending...';
                        button.disabled = true;
                        
                        // Re-enable button after 5 seconds
                        setTimeout(() => {
                            button.innerHTML = originalText;
                            button.disabled = false;
                        }, 5000);
                    }
                });
            }
        });

        // Auto-focus on main action button
        document.addEventListener('DOMContentLoaded', function() {
            const returnButton = document.querySelector('a[href*="login"]');
            if (returnButton) {
                returnButton.focus();
            }
        });
    </script>
</body>
</html>
