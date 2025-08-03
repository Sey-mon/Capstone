<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration System Test - BMI System</title>
    <link href="{{ asset('css/pages.css') }}" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔧 Registration System Test</h1>
            <p>Comprehensive test of your registration and authentication system</p>
        </div>

        <div class="test-results">
            @foreach($tests as $testKey => $test)
                <div class="card test-card {{ $test['status'] }}">
                    <div class="card-header">
                        @if($test['status'] === 'passed')
                            <span class="status-icon">✅</span>
                        @else
                            <span class="status-icon">❌</span>
                        @endif
                        <h3>{{ $test['name'] }}</h3>
                        <span class="status-badge status-{{ $test['status'] }}">
                            {{ ucfirst($test['status']) }}
                        </span>
                    </div>
                    <div class="card-body">
                        @if($test['status'] === 'passed' && isset($test['details']))
                            <div class="details-grid">
                                @foreach($test['details'] as $key => $value)
                                    <div class="detail-item">
                                        <label>{{ $key }}:</label>
                                        <span class="value">
                                            @if(is_array($value))
                                                {{ json_encode($value) }}
                                            @else
                                                {{ $value }}
                                            @endif
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @elseif($test['status'] === 'failed' && isset($test['error']))
                            <div class="error-details">
                                <strong>Error:</strong> {{ $test['error'] }}
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div class="card">
            <div class="card-header">
                <h3>Registration Test Summary</h3>
            </div>
            <div class="card-body">
                @php
                    $passedTests = collect($tests)->where('status', 'passed')->count();
                    $totalTests = count($tests);
                    $failedTests = $totalTests - $passedTests;
                @endphp
                
                <div class="summary-stats">
                    <div class="stat-item passed">
                        <span class="stat-number">{{ $passedTests }}</span>
                        <span class="stat-label">Passed</span>
                    </div>
                    <div class="stat-item failed">
                        <span class="stat-number">{{ $failedTests }}</span>
                        <span class="stat-label">Failed</span>
                    </div>
                    <div class="stat-item total">
                        <span class="stat-number">{{ $totalTests }}</span>
                        <span class="stat-label">Total Tests</span>
                    </div>
                </div>

                @if($failedTests === 0)
                    <div class="alert alert-success">
                        <strong>🎉 All tests passed!</strong> Your registration system is properly configured and ready to use.
                    </div>
                @else
                    <div class="alert alert-warning">
                        <strong>⚠️ Some tests failed.</strong> Please check the failed tests above and fix any configuration issues.
                    </div>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3>Create Test User</h3>
            </div>
            <div class="card-body">
                <p>Test the complete registration flow by creating a test user:</p>
                
                <form method="POST" action="{{ route('test.user.create') }}" class="form">
                    @csrf
                    <div class="form-group">
                        <label for="email">Test Email Address:</label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="{{ old('email', 'shim40907@gmail.com') }}" 
                            required
                            class="form-control"
                            placeholder="Enter email to create test user"
                        >
                        @error('email')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="test_type">Test Type:</label>
                        <select id="test_type" name="test_type" class="form-control">
                            <option value="simple">Simple Test (Basic fields only)</option>
                            <option value="full">Full Test (All registration fields)</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        👤 Create Test User & Send Verification Email
                    </button>
                </form>
                
                <div class="test-info" style="margin-top: 20px; padding: 15px; background: #f8f9fa; border-radius: 6px;">
                    <h4>What this test does:</h4>
                    <ul>
                        <li>✅ Creates a new user record in the database</li>
                        <li>✅ Generates a unique Employee ID</li>
                        <li>✅ Fires the Laravel Registered event</li>
                        <li>✅ Sends email verification notification via Brevo</li>
                        <li>✅ Tests the complete registration workflow</li>
                    </ul>
                    <p><strong>Note:</strong> Default password is "password123" for test users.</p>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3>Quick Registration Test</h3>
            </div>
            <div class="card-body">
                <p>To test the actual registration process:</p>
                <ol class="test-steps">
                    <li>Go to the <a href="{{ route('register') }}" target="_blank">Registration Page</a></li>
                    <li>Fill in the form with test data</li>
                    <li>Submit the form</li>
                    <li>Check your email for the verification link</li>
                    <li>Click the verification link to complete registration</li>
                    <li>Try logging in with the new account</li>
                </ol>
                
                <div class="test-buttons">
                    <a href="{{ route('register') }}" class="btn btn-primary" target="_blank">
                        👤 Open Registration Form
                    </a>
                    <a href="{{ route('login') }}" class="btn btn-secondary" target="_blank">
                        🔑 Open Login Form
                    </a>
                </div>
            </div>
        </div>

        <div class="navigation">
            <a href="{{ route('test.email') }}" class="btn btn-secondary">
                📧 Test Email System
            </a>
            <button onclick="window.location.reload()" class="btn btn-outline">
                🔄 Refresh Tests
            </button>
            <a href="{{ url('/') }}" class="btn btn-outline">
                🏠 Back to Home
            </a>
        </div>
    </div>

    <script src="{{ asset('js/pages.js') }}"></script>
</body>
</html>
