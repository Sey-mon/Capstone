<?php
// Add this to your config/services.php
return [
    'malnutrition' => [
        'api_url' => env('MALNUTRITION_API_URL', 'http://127.0.0.1:8000'),
        'timeout' => env('MALNUTRITION_API_TIMEOUT', 30),
    ],
];