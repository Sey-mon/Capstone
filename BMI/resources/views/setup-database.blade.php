<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Setup - BMI Laravel</title>
    <link rel="stylesheet" href="{{ asset('css/pages.css') }}">
</head>
<body class="setup-body">
    <div class="container">
        <div class="header">
            <h1>🔧 Database Setup Guide</h1>
            <p>BMI Laravel Project with Admin/User Authentication</p>
        </div>

        <div class="status error">
            <strong>❌ Current Issue:</strong><br>
            <code>SQLSTATE[HY000] [1045] Access denied for user 'root'@'localhost'</code><br>
            Your MySQL credentials need to be configured properly.
        </div>

        <div class="step">
            <h3>Step 1: Start XAMPP Services</h3>
            <ol>
                <li>Open <strong>XAMPP Control Panel</strong></li>
                <li>Start <strong>Apache</strong> service</li>
                <li>Start <strong>MySQL</strong> service</li>
                <li>Make sure both show "Running" status</li>
            </ol>
        </div>

        <div class="step">
            <h3>Step 2: Check MySQL Access</h3>
            <p>Try accessing phpMyAdmin:</p>
            <ol>
                <li>Open your browser</li>
                <li>Go to: <a href="http://localhost/phpmyadmin" target="_blank">http://localhost/phpmyadmin</a></li>
                <li>If it asks for credentials, note them down</li>
                <li>If it opens without credentials, MySQL has no password</li>
            </ol>
        </div>

        <div class="step">
            <h3>Step 3: Create Database</h3>
            <p>In phpMyAdmin:</p>
            <ol>
                <li>Click on <strong>"Databases"</strong> tab</li>
                <li>Enter database name: <code>bmi_laravel</code></li>
                <li>Click <strong>"Create"</strong></li>
            </ol>
        </div>

        <div class="step">
            <h3>Step 4: Update .env Configuration</h3>
            <p>Based on your phpMyAdmin access, update your <code>.env</code> file:</p>
            
            <p><strong>If phpMyAdmin opened without password:</strong></p>
            <div class="code">
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bmi_laravel
DB_USERNAME=root
DB_PASSWORD=
            </div>

            <p><strong>If phpMyAdmin required password:</strong></p>
            <div class="code">
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bmi_laravel
DB_USERNAME=root
DB_PASSWORD=your_actual_password
            </div>
        </div>

        <div class="step">
            <h3>Step 5: Run Database Commands</h3>
            <p>Open Command Prompt in your project directory and run:</p>
            <div class="code">
# Clear config cache
php artisan config:clear

# Run migrations to create tables
php artisan migrate

# Seed database with test users
php artisan db:seed
            </div>
        </div>

        <div class="step">
            <h3>Step 6: Test Authentication</h3>
            <p>After successful setup, you can login with:</p>
            <div class="status success">
                <strong>Admin User:</strong> admin@example.com / password<br>
                <strong>Regular User:</strong> user@example.com / password
            </div>
        </div>

        <div class="center-block">
            <a href="{{ url('/home') }}" class="btn btn-primary">← Back to Home</a>
            <a href="{{ url('/test') }}" class="btn btn-success">Test Page</a>
        </div>

        <div class="status warning">
            <strong>💡 Alternative: Use SQLite (Easier Setup)</strong><br>
            If MySQL continues to cause issues, you can switch to SQLite:
            <ol>
                <li>Change <code>DB_CONNECTION=sqlite</code> in .env</li>
                <li>Comment out other DB_ lines</li>
                <li>Run <code>php artisan migrate</code></li>
            </ol>
        </div>
    </div>
    <script src="{{ asset('js/pages.js') }}"></script>
</body>
</html>
