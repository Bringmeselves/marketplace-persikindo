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

    'rajaongkir' => [
        'key' => env('RAJAONGKIR_API_KEY'),         // API Key kamu
        'account_type' => env('RAJAONGKIR_ACCOUNT_TYPE', 'starter'), // default starter
        'base_url' => env('RAJAONGKIR_BASE_URL', 'https://api.rajaongkir.com/starter'), // bisa diubah untuk 'basic' atau 'pro'
    ],

    'komerce' => [
    'key' => env('KOMERCE_API_KEY'),
    ],

    'google' => [
    'client_id' => env('GOOGLE_CLIENT_ID'),
    'client_secret' => env('GOOGLE_CLIENT_SECRET'),
    'redirect' => env('GOOGLE_REDIRECT_URI'),
    ],

    'waha' => [
    'base_url' => env('WHATSAPP_WAHA_BASE_URL', 'http://rahmath.works:3000'),
    'api_key' => env('WHATSAPP_WAHA_API_KEY'),
    'session' => env('WHATSAPP_WAHA_SESSION', 'default'),
],

    'fonnte' => [
        'token' => env('FONNTE_TOKEN', 'V721djVecD4qgEVS4nJd'), // Ganti dengan token Fonnte kamu
        'base_url' => env('FONNTE_BASE_URL', 'https://api.fonnte.com/send'), // URL endpoint Fonnte
    ],  

];
