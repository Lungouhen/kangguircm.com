<?php

declare(strict_types=1);

return [
    'enabled' => (bool) env('ANALYTICS_ENABLED', true),
    'respect_dnt' => true,
    'require_consent' => (bool) env('ANALYTICS_REQUIRE_CONSENT', true),
    'deduplication_minutes' => (int) env('ANALYTICS_DEDUP_MINUTES', 30),
    'retention_days' => (int) env('ANALYTICS_RETENTION_DAYS', 180),
    'trust_geo_headers' => (bool) env('ANALYTICS_TRUST_GEO_HEADERS', false),
    'geo_headers' => [
        'country' => env('ANALYTICS_GEO_COUNTRY_HEADER', 'CF-IPCountry'),
        'region' => env('ANALYTICS_GEO_REGION_HEADER', 'X-Geo-Region'),
        'city' => env('ANALYTICS_GEO_CITY_HEADER', 'X-Geo-City'),
        'organization' => env('ANALYTICS_GEO_ORGANIZATION_HEADER', 'X-Geo-Organization'),
    ],
    'exclude_paths' => ['admin*','login','register','preview/*','up','robots.txt','sitemap.xml','css/*'],
];
