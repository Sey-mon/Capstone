<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - LakasApp</title>
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
            <h2>Reset Password</h2>
            <p class="reset-password-text">
                Enter your new password below to complete the password reset process.
            </p>

            <!-- Error Messages -->
            @if (session('error'))
                <div class="error-message">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.store') }}">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email Address -->
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input 
                        id="email" 
                        class="form-input" 
                        type="email" 
                        name="email" 
                        value="{{ old('email', $request->email) }}" 
                        required 
                        autofocus 
                        autocomplete="username"
                        placeholder="Enter your email address"
                    />
                    @error('email')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password">New Password</label>
                    <input 
                        id="password" 
                        class="form-input"
                        type="password"
                        name="password"
                        required 
                        autocomplete="new-password"
                        placeholder="Enter your new password"
                        pattern="[A-Za-z0-9@#$%^&*!]{8,}"
                        minlength="8"
                    />
                    <small class="form-help-text">
                        Password must be at least 8 characters long<br>
                        ✓ Allowed: Letters (a-z, A-Z), Numbers (0-9), Special chars (@#$%^&*!)
                    </small>
                    @error('password')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="form-group">
                    <label for="password_confirmation">Confirm New Password</label>
                    <input 
                        id="password_confirmation" 
                        class="form-input"
                        type="password"
                        name="password_confirmation" 
                        required 
                        autocomplete="new-password"
                        placeholder="Confirm your new password"
                    />
                    @error('password_confirmation')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="auth-btn">
                    Reset Password
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
