<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Brevo Email Test - BMI System</title>
    <link href="{{ asset('css/pages.css') }}" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📧 Brevo Email API Test</h1>
            <p>Test your Brevo email configuration and send a test email</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                <strong>✅ Success!</strong> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                <strong>❌ Error!</strong> {{ session('error') }}
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h3>Current Email Configuration</h3>
            </div>
            <div class="card-body">
                <div class="config-grid">
                    <div class="config-item">
                        <label>Mail Driver:</label>
                        <span class="value">{{ config('mail.default') }}</span>
                    </div>
                    <div class="config-item">
                        <label>SMTP Host:</label>
                        <span class="value">{{ config('mail.mailers.smtp.host') }}</span>
                    </div>
                    <div class="config-item">
                        <label>SMTP Port:</label>
                        <span class="value">{{ config('mail.mailers.smtp.port') }}</span>
                    </div>
                    <div class="config-item">
                        <label>Encryption:</label>
                        <span class="value">{{ config('mail.mailers.smtp.encryption') }}</span>
                    </div>
                    <div class="config-item">
                        <label>Username:</label>
                        <span class="value">{{ config('mail.mailers.smtp.username') }}</span>
                    </div>
                    <div class="config-item">
                        <label>From Address:</label>
                        <span class="value">{{ config('mail.from.address') }}</span>
                    </div>
                    <div class="config-item">
                        <label>From Name:</label>
                        <span class="value">{{ config('mail.from.name') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('test.email.send') }}" class="form">
            @csrf
            <div class="card">
                <div class="card-header">
                    <h3>Send Test Email</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="email">Email Address:</label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="{{ old('email', 'shim40907@gmail.com') }}" 
                            required
                            class="form-control"
                            placeholder="Enter email address to send test email"
                        >
                        @error('email')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">
                        📧 Send Test Email
                    </button>
                </div>
            </div>
        </form>

        <div class="card">
            <div class="card-header">
                <h3>What This Test Does</h3>
            </div>
            <div class="card-body">
                <ul class="test-list">
                    <li>✅ Verifies your Brevo SMTP configuration</li>
                    <li>✅ Tests the connection to Brevo's email servers</li>
                    <li>✅ Sends a formatted HTML email with system information</li>
                    <li>✅ Includes both HTML and plain text versions</li>
                    <li>✅ Confirms your email settings are working for user registration</li>
                </ul>
            </div>
        </div>

        <div class="navigation">
            <a href="{{ route('test.registration') }}" class="btn btn-secondary">
                🔧 Test Registration System
            </a>
            <a href="{{ url('/') }}" class="btn btn-outline">
                🏠 Back to Home
            </a>
        </div>
    </div>

    <script src="{{ asset('js/pages.js') }}"></script>
    <script>
        // Auto-focus on email input
        document.addEventListener('DOMContentLoaded', function() {
            const emailInput = document.getElementById('email');
            if (emailInput) {
                emailInput.focus();
                emailInput.select();
            }
        });
    </script>
</body>
</html>
