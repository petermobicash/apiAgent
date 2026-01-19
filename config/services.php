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

    'mobicash' => [
        'base_url' => env('MOBICASH_BASE_URL', 'https://testbox.mobicash.rw/mobicore/api'),
        'timeout' => env('MOBICASH_TIMEOUT', 30),
    ],

    'access_bank' => [
        'base_url' => env('ACCESS_BANK_BASE_URI', 'https://api.accessbank.com'),
        'api_key' => env('ACCESS_BANK_API_KEY', 'default_api_key'),
        'secret_key' => env('ACCESS_BANK_WEBHOOK_SECRET', 'default_secret'),
    ],

    'cyclos' => [
        'base_url' => env('CYCLOS_BASE_URL'),
        'system_username' => env('CYCLOS_SYSTEM_USERNAME'),
        'system_password' => env('CYCLOS_SYSTEM_PASSWORD'),
    ],

    'ltss' => [
        'validation_url' => env('LTSS_VALIDATION_URL'),
        'contribution_url' => env('LTSS_CONTRIBUTION_URL'),
        'mobicore_payment_url' => env('LTSS_MOBICORE_PAYMENT_URL'),
        'validation_username' => env('LTSS_VALIDATION_USERNAME'),
        'validation_password' => env('LTSS_VALIDATION_PASSWORD'),
        'contribution_username' => env('LTSS_CONTRIBUTION_USERNAME'),
        'contribution_password' => env('LTSS_CONTRIBUTION_PASSWORD'),
        'timeout' => env('LTSS_TIMEOUT', 30),
        'subject' => env('LTSS_SUBJECT'),
    ],

    'rnit' => [
        'nid_validation_url' => env('RNIT_NID_VALIDATION_URL'),
        'mobicore_payment_url' => env('RNIT_MOBICORE_PAYMENT_URL'),
        'timeout' => env('RNIT_TIMEOUT', 30),
        'dependent_agent' => [
            'description' => 'T8:RNIT Contribution Payment(Post Office Agent Level 2 Test)',
            'subject' => env('RNIT_DEPENDENT_AGENT_SUBJECT'),
            'type' => env('RNIT_DEPENDENT_AGENT_TYPE'),
        ],
        'ddi_broker_dependent_agent' => [
            'description' => 'T9:RNIT Contribution Payment(DDI Agent Level 2 Test)',
            'subject' => env('RNIT_DDI_BROKER_SUBJECT'),
            'type' => env('RNIT_DDI_BROKER_TYPE'),
        ],
        'independent_agent' => [
            'description' => 'T8:RNIT Contribution Payment(Post Office Agent Level 2 Test)',
            'subject' => env('RNIT_INDEPENDENT_AGENT_SUBJECT'),
            'type' => env('RNIT_INDEPENDENT_AGENT_TYPE'),
        ],
        'individual_clients' => [
            'description' => 'T8:RNIT Contribution Payment(Post Office Agent Level 2 Test)',
            'subject' => env('RNIT_INDIVIDUAL_CLIENTS_SUBJECT'),
            'type' => env('RNIT_INDIVIDUAL_CLIENTS_TYPE'),
        ],
    ],

];
