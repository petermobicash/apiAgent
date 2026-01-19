<?php

return [
    'base_uri' => env('MOBICASH_BASE_URI', 'https://testbox.mobicash.rw/mobicore/api/'),
    'credentials' => [
        'equity' => env('MOBICASH_EQUITY_CREDENTIALS'),
        'bpr' => env('MOBICASH_BPR_CREDENTIALS'),
        'coge' => env('MOBICASH_COGE_CREDENTIALS'),
        'gt' => env('MOBICASH_GT_CREDENTIALS'),
        'im' => env('MOBICASH_IM_CREDENTIALS'),
        'ria' => env('MOBICASH_RIA_CREDENTIALS'),
        'delayed_commission' => env('MOBICASH_DELAYED_COMMISSION_CREDENTIALS'),
    ],
];