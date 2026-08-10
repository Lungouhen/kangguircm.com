<?php

declare(strict_types=1);

return [
    'lead' => [
        'email_enabled' => (bool) env('LEAD_EMAIL_NOTIFICATIONS', true),
        'email_recipients' => array_values(array_filter(array_map('trim', explode(',', (string) env('LEAD_NOTIFICATION_EMAILS', ''))))),
        'whatsapp_enabled' => (bool) env('WHATSAPP_NOTIFICATIONS_ENABLED', false),
    ],
    'whatsapp' => [
        'provider' => 'meta',
        'graph_version' => env('WHATSAPP_GRAPH_VERSION', 'v23.0'),
        'access_token' => env('WHATSAPP_ACCESS_TOKEN'),
        'phone_number_id' => env('WHATSAPP_PHONE_NUMBER_ID'),
        'business_account_id' => env('WHATSAPP_BUSINESS_ACCOUNT_ID'),
        'app_secret' => env('WHATSAPP_APP_SECRET'),
        'verify_token' => env('WHATSAPP_VERIFY_TOKEN'),
        'recipients' => array_values(array_filter(array_map('trim', explode(',', (string) env('WHATSAPP_RECIPIENTS', ''))))),
        'template' => env('WHATSAPP_LEAD_TEMPLATE', 'new_website_lead'),
        'template_language' => env('WHATSAPP_TEMPLATE_LANGUAGE', 'en_US'),
        'connect_timeout' => (int) env('WHATSAPP_CONNECT_TIMEOUT', 5),
        'timeout' => (int) env('WHATSAPP_TIMEOUT', 10),
    ],
];
