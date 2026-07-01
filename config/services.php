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

    // ── Midtrans Payment Gateway ───────────────────────────
    'midtrans' => [
        'server_key'    => env('MIDTRANS_SERVER_KEY'),
        'client_key'    => env('MIDTRANS_CLIENT_KEY'),
        'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
        'enabled'       => env('MIDTRANS_ENABLED', false),
        'sandbox_url'   => 'https://app.sandbox.midtrans.com/snap/v1',
        'production_url'=> 'https://app.midtrans.com/snap/v1',
    ],

    // ── Fonnte WhatsApp API ────────────────────────────────
    'fonnte' => [
        'token'   => env('FONNTE_TOKEN'),
        'api_url' => 'https://api.fonnte.com/send',
    ],

    // ── Admin Contact (untuk WA notifications) ─────────────
    'admin_phone' => env('ADMIN_PHONE', '085877153319'),

];
