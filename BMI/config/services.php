<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Malnutrition Assessment API Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for the FastAPI backend that handles ML predictions
    | and treatment recommendations for malnutrition assessment.
    |
    */
    'malnutrition' => [
        'api_url' => env('MALNUTRITION_API_URL', 'http://127.0.0.1:8081'),
        'api_key' => env('MALNUTRITION_API_KEY', '0mI4mQA975wCrFiDTIoj8UDOrFT0OtEqOKi4DhpRfOBdzch8HyKk58zieQ9I5F3j'),
        'timeout' => env('MALNUTRITION_API_TIMEOUT', 30),
        'retry_attempts' => env('MALNUTRITION_API_RETRY', 3),
        'retry_delay' => env('MALNUTRITION_API_RETRY_DELAY', 1000),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'nutrition_api' => [
        'url' => env('NUTRITION_API_URL', 'https://your-api-endpoint.com/api'),
        'key' => env('NUTRITION_API_KEY', ''),
    ],

];