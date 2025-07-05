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

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'api_oauth' => [
        'client_id' => env('API_OAUTH_CLIENT_ID'),
        'client_secret' => env('API_OAUTH_CLIENT_SECRET'),
        'validate_url' => env('API_OAUTH_VALIDATE_URL'),
        'login_url' => env('API_OAUTH_LOGIN_URL'),
        'register_url' => env('API_OAUTH_REGISTER_URL')
    ],

    'api_history' => [
        'url' => env('API_HISTORY_URL'),
        'task_url' => env('API_HISTORY_TASK_URL'),
    ]
];