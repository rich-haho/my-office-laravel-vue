<?php

return [
    'platform_url' => env('PROTOCOL', 'http') . '://' . env('DOMAIN', 'yourzenoffice.com'),
    'search' => [
        'logs' => [
            'limit' => env('SEARCH_LOGS_LIMIT', 6)
        ]
    ],
    'mails' => [
        'fm_services' => env('MAIL_FM_SERVICES', 'helpline@fleximgroup.com')
    ],
    'superuser' => [
        'mail' => env('SUPER_USER_MAIL', 'superuser@yourzenoffice.com')
    ]
];
