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
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
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

    'openrouter' => [
        'api_key' => env('OPENROUTER_API_KEY'),
        'base_url' => env('OPENROUTER_BASE_URL', 'https://openrouter.ai/api/v1'),
        'profit_multiplier' => env('AI_PROFIT_MULTIPLIER', 2.0),
        'models' => [
            'openai/gpt-4o' => 'GPT-4o (Best for general tasks)',
            'openai/gpt-4o-mini' => 'GPT-4o Mini (Fast & efficient)',
            'anthropic/claude-3.5-sonnet' => 'Claude 3.5 Sonnet (Best for logic)',
            'anthropic/claude-3-haiku' => 'Claude 3 Haiku (Fast)',
            'google/gemini-2.0-flash-001' => 'Gemini 2.0 Flash (Fast & Smart)',
            'google/gemini-pro-1.5' => 'Gemini 1.5 Pro (Advanced Logic)',
            'meta-llama/llama-3.1-70b-instruct' => 'Llama 3.1 70B (Open source)',
            'meta-llama/llama-3.1-8b-instruct' => 'Llama 3.1 8B (Fast, open)',
        ],
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI'),
    ],

];
