<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - BMI Authentication</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>
    <div class="auth-container">
        <div class="auth-header">
            <h1>BMI Login</h1>
            <p>Welcome back! Please sign in to continue</p>
        </div>

        <!-- Session Status -->
        @if (session('status'))
            <div class="success-message">
                {{ session('status') }}
            </div>
        @endif

        <!-- Test Credentials Info -->
        <div class="test-credentials">
            <h4>🔑 Test Credentials</h4>
            <div><strong>Admin:</strong> admin@example.com / password</div>
            <div><strong>User:</strong> user@example.com / password</div>
        </div>

        <form method="POST" action="{{ route('login') }}">
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
                    placeholder="Enter your email"
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
                <a href="{{ route('password.request') }}">Forgot your password?</a>
            @endif
            
            @if (Route::has('register'))
                <span style="margin: 0 10px;">•</span>
                <a href="{{ route('register') }}">Create new account</a>
            @endif
            
            <div style="margin-top: 15px;">
                <a href="{{ url('/home') }}">← Back to Home</a>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/auth.js') }}"></script>
</body>
</html>
