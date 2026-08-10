# Enterprise Lead Notifications

## Architecture

Public forms persist an encrypted `MarketingLead` before dispatching `SendNewLeadNotifications` after commit. The queued job sends minimal admin email and optional Meta WhatsApp Business template alerts. Delivery attempts are idempotent and stored in `notification_deliveries`; raw lead messages, access tokens, and complete recipients are never stored in delivery logs.

## Meta setup

1. Create a Meta Business and WhatsApp Business account.
2. Register a dedicated sending number and obtain its Phone Number ID.
3. Create and approve a utility template named by `WHATSAPP_LEAD_TEMPLATE`. Keep the template generic: “A new website lead is ready. Sign in to the secure admin portal.” Do not include PHI or lead message variables.
4. Create a system-user token limited to the required WhatsApp asset and permissions.
5. Configure the webhook URL: `https://your-domain/webhooks/whatsapp`.
6. Configure the webhook verify token and subscribe to message status updates.
7. Store all credentials in a secrets manager or deployment environment.

## Environment

See `.env.example`. `WHATSAPP_RECIPIENTS` is a comma-separated list in E.164 format without spaces. Keep `WHATSAPP_NOTIFICATIONS_ENABLED=false` until the approved template, queue worker, and webhook verification are confirmed.

## Queue

Use Redis where available, otherwise the included database queue tables:

```bash
php artisan queue:work --queue=notifications,default --tries=4 --timeout=30
```

Use Supervisor, systemd, Kubernetes, or Horizon to keep workers running. Monitor `failed_jobs` and Admin → Notification Deliveries.

## Webhook security

POST callbacks require Meta's `X-Hub-Signature-256`, verified using `WHATSAPP_APP_SECRET`. The endpoint is request-size limited and rate-limited. CSRF is excluded only for this signed provider callback.

## Failure behavior

The lead remains safely stored even if all providers are unavailable. Jobs retry with 30-second, 120-second, and 600-second backoff. Successfully sent channels are skipped on retry. Administrators can retry failed deliveries from the dashboard.

## Privacy

WhatsApp is an alert channel, not a lead data store. Never add patient data, full contact messages, insurance details, diagnoses, attachments, or other PHI to the template. The alert should direct authorized staff to the authenticated CMS.
