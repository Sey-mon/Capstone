<?php

// Simple API test script
$apiUrl = 'http://127.0.0.1:8080/api';

// Test the API test endpoint
echo "Testing API endpoint...\n";
$response = file_get_contents($apiUrl . '/test');
echo "Response: " . $response . "\n\n";

// Test user registration
echo "Testing user registration...\n";
$userData = [
    'name' => 'Test User',
    'email' => 'test@example.com',
    'password' => 'password123',
    'password_confirmation' => 'password123',
    'role' => 'user'
];

$options = [
    'http' => [
        'method' => 'POST',
        'header' => 'Content-Type: application/json',
        'content' => json_encode($userData)
    ]
];

$context = stream_context_create($options);
$result = file_get_contents($apiUrl . '/register', false, $context);

if ($result === FALSE) {
    echo "Registration failed\n";
} else {
    echo "Registration response: " . $result . "\n";
}

echo "\nAPI Test completed!\n";
