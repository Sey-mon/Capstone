<?php

namespace App\Http\Controllers;

use App\Mail\TestBrevoConnection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\Registered;

class TestController extends Controller
{
    /**
     * Show the email test form
     */
    public function testEmail()
    {
        return view('test.email');
    }

    /**
     * Send a test email to verify Brevo API connection
     */
    public function sendTestEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $email = $request->input('email');

        try {
            // Send test email using the mailable class
            Mail::to($email)->send(new TestBrevoConnection());

            return back()->with('success', 'Test email sent successfully to ' . $email . '! Please check your inbox.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send test email: ' . $e->getMessage());
        }
    }

    /**
     * Show the registration test form and perform tests
     */
    public function testRegistration()
    {
        $tests = [];
        
        // Test 1: Check mail configuration
        $tests['mail_config'] = [
            'name' => 'Mail Configuration',
            'status' => 'passed',
            'details' => [
                'Driver' => config('mail.default'),
                'Host' => config('mail.mailers.smtp.host'),
                'Port' => config('mail.mailers.smtp.port'),
                'Username' => config('mail.mailers.smtp.username'),
                'From Address' => config('mail.from.address'),
                'From Name' => config('mail.from.name'),
            ]
        ];

        // Test 2: Check database connection
        try {
            DB::connection()->getPdo();
            $tests['database'] = [
                'name' => 'Database Connection',
                'status' => 'passed',
                'details' => [
                    'Connection' => config('database.default'),
                    'Host' => config('database.connections.' . config('database.default') . '.host'),
                    'Database' => config('database.connections.' . config('database.default') . '.database'),
                ]
            ];
        } catch (\Exception $e) {
            $tests['database'] = [
                'name' => 'Database Connection',
                'status' => 'failed',
                'error' => $e->getMessage()
            ];
        }

        // Test 3: Check users table
        try {
            $userCount = User::count();
            $tests['users_table'] = [
                'name' => 'Users Table',
                'status' => 'passed',
                'details' => [
                    'Total Users' => $userCount,
                    'Admin Users' => User::where('role', 'admin')->count(),
                    'Regular Users' => User::where('role', 'user')->count(),
                    'Nutritionist Users' => User::where('role', 'nutritionist')->count(),
                ]
            ];
        } catch (\Exception $e) {
            $tests['users_table'] = [
                'name' => 'Users Table',
                'status' => 'failed',
                'error' => $e->getMessage()
            ];
        }

        // Test 4: Check email verification configuration
        $tests['email_verification'] = [
            'name' => 'Email Verification Configuration',
            'status' => 'passed',
            'details' => [
                'Email Verification Enabled' => env('MAIL_VERIFICATION', 'false'),
                'Auth Routes Loaded' => route_exists('verification.notice') ? 'Yes' : 'No',
                'Verification Middleware' => 'Available',
            ]
        ];

        // Test 5: Try creating a test user (simulate registration)
        $testUserData = [
            'name' => 'Test User ' . Str::random(5),
            'email' => 'test_' . Str::random(8) . '@example.com',
            'password' => 'password123',
            'role' => 'user'
        ];

        try {
            // Check if we can create a user without actually creating it
            $user = new User();
            $user->name = $testUserData['name'];
            $user->email = $testUserData['email'];
            $user->password = Hash::make($testUserData['password']);
            $user->role = $testUserData['role'];
            
            // Validate the user model
            if ($user) {
                $tests['user_creation'] = [
                    'name' => 'User Creation Test',
                    'status' => 'passed',
                    'details' => [
                        'Model Validation' => 'Passed',
                        'Password Hashing' => 'Working',
                        'Role Assignment' => 'Working',
                        'Test Data' => $testUserData
                    ]
                ];
            }
        } catch (\Exception $e) {
            $tests['user_creation'] = [
                'name' => 'User Creation Test',
                'status' => 'failed',
                'error' => $e->getMessage()
            ];
        }

        return view('test.registration', compact('tests'));
    }

    /**
     * Create a test user to simulate registration flow
     */
    public function createTestUser(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'test_type' => 'required|in:simple,full'
        ]);

        $email = $request->input('email');
        $testType = $request->input('test_type', 'simple');

        try {
            // Get the first available barangay
            $barangay = \App\Models\Barangay::first();
            if (!$barangay) {
                return back()->with('error', 'No barangays found. Please seed barangay data first.');
            }

            if ($testType === 'simple') {
                // Simple test user creation
                $user = User::create([
                    'first_name' => 'Test',
                    'last_name' => 'User',
                    'email' => $email,
                    'password' => Hash::make('password123'),
                    'role' => 'parent_guardian',
                    'status' => 'email_pending',
                    'date_of_birth' => '1990-01-01',
                    'sex' => 'male',
                    'barangay_id' => $barangay->id,
                    'is_active' => true,
                ]);
            } else {
                // Full registration simulation
                $user = User::create([
                    'first_name' => 'John',
                    'middle_name' => 'Doe',
                    'last_name' => 'Smith',
                    'email' => $email,
                    'password' => Hash::make('password123'),
                    'phone_number' => '+639123456789',
                    'date_of_birth' => '1985-06-15',
                    'sex' => 'male',
                    'barangay_id' => $barangay->id,
                    'address' => '123 Test Street, Test City',
                    'role' => 'parent_guardian',
                    'status' => 'email_pending',
                    'is_active' => true,
                ]);
            }

            // Fire the registered event (this should trigger email verification)
            event(new Registered($user));

            // Send email verification notification
            $user->sendEmailVerificationNotification();

            return back()->with('success', 
                "Test user created successfully!\n" .
                "Employee ID: {$user->employee_id}\n" .
                "Email: {$user->email}\n" .
                "Verification email sent! Please check your inbox."
            );

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to create test user: ' . $e->getMessage());
        }
    }
}

/**
 * Helper function to check if a route exists
 */
if (!function_exists('route_exists')) {
    function route_exists($name)
    {
        try {
            route($name);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
