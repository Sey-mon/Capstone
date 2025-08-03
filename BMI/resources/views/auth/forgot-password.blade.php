<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - LakasApp</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
    <div class="login-card">
        <div class="login-header">
            <svg fill="none" viewBox="0 0 48 48" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M24 42s-12-7.333-12-18a8 8 0 0 1 16 0 8 8 0 0 1 16 0c0 10.667-12 18-12 18z"/></svg>
            <h1>LakasApp</h1>
            <p>Smart Nutrition for Kids</p>
        </div>
        <div class="login-body">
            <h2>Forgot Password</h2>
            <p class="forgot-password-text">
                Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.
            </p>

            <!-- Session Status -->
            @if (session('status'))
                <div class="success-message">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Error Messages -->
            @if (session('error'))
                <div class="error-message">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <!-- Email Address -->
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input 
                        id="email" 
                        class="form-input" 
                        type="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        required 
                        autofocus 
                        autocomplete="username"
                        placeholder="Enter your email address"
                    />
                    @error('email')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="auth-btn">
                    Email Password Reset Link
                </button>
            </form>

            <div class="auth-links">
                <a href="{{ route('login') }}" style="color: #2196f3; text-decoration: underline; font-weight: 500;">Back to Login</a>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/auth.js') }}"></script>
</body>
</html>
