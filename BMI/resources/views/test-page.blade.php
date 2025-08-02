<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BMI Laravel Test Page</title>
    <link rel="stylesheet" href="{{ asset('css/pages.css') }}">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🚀 BMI Laravel Project</h1>
            <p>Multi-Role Authentication System</p>
        </div>

        <div class="status success">
            <strong>✅ Laravel Application is Running!</strong><br>
            Your Laravel project has been successfully set up.
        </div>

        <div class="status info">
            <strong>📋 Project Features:</strong>
            <ul class="feature-list">
                <li>Laravel 12.x Framework</li>
                <li>Multi-role Authentication (Admin & User)</li>
                <li>Laravel Breeze for Authentication</li>
                <li>Role-based Access Control</li>
                <li>Admin Dashboard</li>
                <li>User Management</li>
                <li>Modern UI with Tailwind CSS</li>
            </ul>
        </div>

        <div class="status warning">
            <strong>⚠️ Database Setup Required</strong><br>
            To use authentication features, you need to:
            <ol>
                <li>Start MySQL in XAMPP Control Panel</li>
                <li>Create database 'bmi_laravel' in phpMyAdmin</li>
                <li>Run: <code>php artisan migrate</code></li>
                <li>Run: <code>php artisan db:seed</code></li>
            </ol>
        </div>

        <div class="actions-section">
            <h3>Quick Actions:</h3>
            <a href="{{ url('/home') }}" class="btn btn-primary">Home Page</a>
            <a href="{{ url('/login') }}" class="btn btn-success">Login</a>
            <a href="{{ url('/register') }}" class="btn btn-secondary">Register</a>
        </div>

        <div class="test-users-info">
            <h4>Default Test Users (after running seeders):</h4>
            <p><strong>Admin User:</strong> admin@example.com / password</p>
            <p><strong>Regular User:</strong> user@example.com / password</p>
        </div>

        <div class="version-info">
            <p>Laravel Version: {{ app()->version() }} | PHP Version: {{ phpversion() }}</p>
            <p>Environment: {{ app()->environment() }}</p>
        </div>
    </div>
    <script src="{{ asset('js/pages.js') }}"></script>
</body>
</html>
