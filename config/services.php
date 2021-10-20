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
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    // 'google' => [
    //     'client_id'     => env('GOOGLE_CLIENT_ID', '190896857546-uud8pjeuoc5e5i6sp2h40avupif2mb04.apps.googleusercontent.com'),
    //     'client_secret' => env('GOOGLE_CLIENT_SECRET', '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'),
    //     'redirect'      => env('GOOGLE_URL', 'http://localhost:3000/login/google'),
    // ],
    'google' => [
        'client_id'     => env('GOOGLE_CLIENT_ID', '37192225670-f4gb7ohcfij72kvu5mfn5qtbque098q8.apps.googleusercontent.com'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET', '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'),
        'redirect'      => 'http://localhost:8000/api/auth/google/callback',
    ],

    'facebook' => [
        'client_id'     => env('FACEBOOK_CLIENT_ID', '407662997406507'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET', '5c80d0f7e2762ec789507e95ea7e403f'),
        'redirect'      => env('FACEBOOK_URL', 'https://091d-8-29-105-26.ngrok.io/login/facebook/callback'),
    ],

];
