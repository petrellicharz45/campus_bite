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

    'flutterwave' => [
        'public_key' => env('FLW_PUBLIC_KEY'),
        'secret_key' => env('FLW_SECRET_KEY'),
        'secret_hash' => env('FLW_SECRET_HASH'),
        'base_url' => env('FLW_BASE_URL', 'https://api.flutterwave.com/v3'),
        'currency' => env('FLW_CURRENCY', 'UGX'),
    ],

    'social' => [
        'whatsapp_number' => env('SOCIAL_WHATSAPP_NUMBER'),
        'facebook_url' => env('SOCIAL_FACEBOOK_URL'),
        'instagram_url' => env('SOCIAL_INSTAGRAM_URL'),
        'twitter_url' => env('SOCIAL_TWITTER_URL'),
        'youtube_url' => env('SOCIAL_YOUTUBE_URL'),
        'youtube_embed_url' => env('SOCIAL_YOUTUBE_EMBED_URL'),
    ],

];
