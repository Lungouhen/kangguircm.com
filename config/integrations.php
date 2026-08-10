<?php

declare(strict_types=1);

return [
 'turnstile'=>['enabled'=>(bool)env('TURNSTILE_ENABLED',false),'site_key'=>env('TURNSTILE_SITE_KEY'),'secret_key'=>env('TURNSTILE_SECRET_KEY'),'timeout'=>5],
 'google_tag_manager'=>['container_id'=>env('GTM_CONTAINER_ID')],
 'google_analytics'=>['measurement_id'=>env('GA4_MEASUREMENT_ID')],
 'search_console'=>['verification'=>env('GOOGLE_SITE_VERIFICATION')],
 'meta_pixel'=>['pixel_id'=>env('META_PIXEL_ID')],
 'crm_webhook'=>['enabled'=>(bool)env('CRM_WEBHOOK_ENABLED',false),'url'=>env('CRM_WEBHOOK_URL'),'secret'=>env('CRM_WEBHOOK_SECRET'),'timeout'=>10],
];
