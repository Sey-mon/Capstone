<?php

// API Authentication Test
$apiUrl = 'http://127.0.0.1:8080/api';

// Test user login
echo "Testing user login...\n";
$loginData = [
    'email' => 'test@example.com',
    'password' => 'password123'
];

$options = [
    'http' => [
        'method' => 'POST',
        'header' => 'Content-Type: application/json',
        'content' => json_encode($loginData)
    ]
];

$context = stream_context_create($options);
$result = file_get_contents($apiUrl . '/login', false, $context);

if ($result === FALSE) {
    echo "Login failed\n";
    exit;
}

$loginResponse = json_decode($result, true);
echo "Login response: " . $result . "\n\n";

// Extract token for authenticated requests
$token = $loginResponse['token'] ?? null;

if ($token) {
    // Test authenticated endpoint
    echo "Testing authenticated endpoint...\n";
    $authOptions = [
        'http' => [
            'method' => 'GET',
            'header' => 'Authorization: Bearer ' . $token
        ]
    ];
    
    $authContext = stream_context_create($authOptions);
    $userResult = file_get_contents($apiUrl . '/user', false, $authContext);
    
    if ($userResult === FALSE) {
        echo "User endpoint failed\n";
    } else {
        echo "User endpoint response: " . $userResult . "\n\n";
    }
    
    // Test dashboard stats
    echo "Testing dashboard stats...\n";
    $statsResult = file_get_contents($apiUrl . '/dashboard-stats', false, $authContext);
    
    if ($statsResult === FALSE) {
        echo "Dashboard stats failed\n";
    } else {
        echo "Dashboard stats response: " . $statsResult . "\n\n";
    }
}

echo "Authentication test completed!\n";
