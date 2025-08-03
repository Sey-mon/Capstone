<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply as Nutritionist - LakasApp</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
</head>
<body>
    <div class="auth-container register-container">
        <div class="auth-header">
            <h1>Apply as Nutritionist</h1>
            <p>Submit your credentials for admin approval</p>
        </div>
        <div class="mb-4 text-sm text-gray-700 bg-yellow-50 border-l-4 border-yellow-400 p-3 rounded">
            Your application will be reviewed by an admin. You will be notified by email once approved or rejected.
        </div>
        <form method="POST" action="{{ route('nutritionist.apply.submit') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-section">
                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input id="name" class="form-input" type="text" name="name" value="{{ old('name') }}" required autofocus placeholder="Enter your full name" />
                        @error('name')<div class="error-message">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input id="email" class="form-input" type="email" name="email" value="{{ old('email') }}" required placeholder="Enter your email" />
                        @error('email')<div class="error-message">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input id="password" class="form-input" type="password" name="password" required placeholder="Choose a strong password" />
                        <small style="color:#888;">Minimum 8 characters, at least one letter and one number.</small>
                        @error('password')<div class="error-message">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">Confirm Password</label>
                        <input id="password_confirmation" class="form-input" type="password" name="password_confirmation" required placeholder="Confirm your password" />
                        @error('password_confirmation')<div class="error-message">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="id_document">Valid ID (JPG, PNG, PDF)</label>
                        <input id="id_document" class="form-input" type="file" name="id_document" accept=".jpg,.jpeg,.png,.pdf" required />
                        @error('id_document')<div class="error-message">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="certificate">Nutrition Certificate (JPG, PNG, PDF)</label>
                        <input id="certificate" class="form-input" type="file" name="certificate" accept=".jpg,.jpeg,.png,.pdf" required />
                        @error('certificate')<div class="error-message">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
            <button type="submit" class="auth-btn register-btn">Submit Application</button>
        </form>
        <div class="auth-links" style="margin-top:2rem;">
            <a href="{{ route('login') }}">&larr; Back to Login</a>
        </div>
    </div>
</body>
</html> 