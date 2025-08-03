<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LakasApp: Smart Nutrition for Kids</title>
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

            <!-- Session Status -->
            @if (session('status'))
                <div class="success-message">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Success Messages (for email verification, etc.) -->
            @if (session('success'))
                <div class="success-message">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Error Messages -->
            @if (session('error'))
                <div class="error-message">
                    {{ session('error') }}
                </div>
            @endif


            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Address -->
                <div class="form-group">
                    <label for="email">Username</label>
                    <input 
                        id="email" 
                        class="form-input" 
                        type="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        required 
                        autofocus 
                        autocomplete="username"
                        placeholder="Enter your username"
                    />
                    @error('email')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password">Password</label>
                    <input 
                        id="password" 
                        class="form-input"
                        type="password"
                        name="password"
                        required 
                        autocomplete="current-password"
                        placeholder="Enter your password"
                    />
                    @error('password')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="checkbox-container">
                    <input id="remember_me" type="checkbox" name="remember">
                    <label for="remember_me">Remember me for 30 days</label>
                </div>

                <button type="submit" class="auth-btn">
                    Sign In
                </button>
            </form>

            <div class="auth-links">
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" style="color: #e53935; font-weight: bold; display: block; margin-bottom: 0.7rem;">Forgot your password?</a>
                @endif
                <div style="margin-top: 1.5rem; display: flex; flex-direction: column; gap: 0.5rem; align-items: center;">
                    <!-- Nutritionist application link moved below as text link -->
                </div>
                <div style="margin-top: 0.7rem; text-align: center;">
                    <a href="{{ route('register') }}" style="color: #2196f3; text-decoration: underline; font-weight: 500; font-size: 1rem;">Create an Account</a>
                    <span style="margin: 0 8px; color: #bbb;">|</span>
                    <a href="{{ route('nutritionist.apply') }}" style="color: #2196f3; text-decoration: underline; font-weight: 500; font-size: 1rem;">Apply as Nutritionist</a>
                </div>
                <div style="margin-top: 1.2rem; text-align: center; font-size: 0.95rem; color: #666;">
                    By registering or applying, you agree to our
                    <a href="/terms" target="_blank" style="color: #2196f3; text-decoration: underline;">Terms & Conditions</a>
                    and
                    <a href="/privacy" target="_blank" style="color: #2196f3; text-decoration: underline;">Privacy Policy</a>.
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/auth.js') }}"></script>
    <script src="{{ asset('js/login.js') }}"></script>
</body>
</html>
