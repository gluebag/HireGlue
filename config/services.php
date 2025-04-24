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

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'openai' => [
        'api_key' => env('OPENAI_API_KEY'),
        'organization' => env('OPENAI_ORGANIZATION'),
        'model' => env('OPENAI_MODEL', 'gpt-4o'),
    ],

    'anthropic' => [
        'api_key' => env('ANTHROPIC_API_KEY_HIRE_GLUE'),
        'model' => env('ANTHROPIC_MODEL', 'claude-3-7-sonnet-20250219'),
        'headers' => [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'x-api-key' => env('ANTHROPIC_API_KEY_HIRE_GLUE'),
            'anthropic-version' => '2023-06-01',
            'anthropic-beta' => implode(',', ['output-128k-2025-02-19', 'message-batches-2024-09-24']),
        ]
    ],

    'html_to_markdown' => [
        'api_key' => env('HTML_TO_MARKDOWN_API_KEY', '')
    ],

];
