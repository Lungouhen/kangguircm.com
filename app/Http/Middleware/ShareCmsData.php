<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\LegalPolicy;
use App\Models\MarketingLead;
use App\Models\Menu;
use App\Models\NotificationDelivery;
use App\Models\SiteSetting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class ShareCmsData
{
    public function handle(Request $request, Closure $next): Response
    {
        $site = [
            'name' => SiteSetting::valueOf('site_name', config('app.name')),
            'tagline' => SiteSetting::valueOf('site_tagline', ''),
            'logo' => SiteSetting::valueOf('site_logo', asset('images/logo.svg')),
            'logo_dark' => SiteSetting::valueOf('site_logo_dark', asset('images/logo-dark.svg')),
            'email' => SiteSetting::valueOf('contact_email'),
            'phone' => SiteSetting::valueOf('contact_phone'),
            'address' => SiteSetting::valueOf('contact_address'),
            'footer_text' => SiteSetting::valueOf('footer_text'),
            'copyright' => SiteSetting::valueOf('copyright_text'),
            'facebook' => SiteSetting::valueOf('social_facebook'),
            'linkedin' => SiteSetting::valueOf('social_linkedin'),
            'youtube' => SiteSetting::valueOf('social_youtube'),
            'default_meta_title' => SiteSetting::valueOf('default_meta_title'),
            'default_meta_description' => SiteSetting::valueOf('default_meta_description'),
            'default_social_image' => SiteSetting::valueOf('default_social_image', asset('images/og-default.jpg')),
            'custom_css' => SiteSetting::valueOf('custom_css'),
        ];

        View::share('site', $site);
        View::share('headerMenu', $this->menu('header'));
        View::share('footerMenu', $this->menu('footer'));
        View::share('legalPolicies', Cache::remember('cms_legal_footer', now()->addHour(), fn () => LegalPolicy::where('is_published',true)->where('show_in_footer',true)->whereDate('effective_at','<=',today())->orderBy('title')->get()));
        View::share('hasConsentChoice', $request->cookies->has('cms_consent'));
        View::share('consentCategories', json_decode((string)$request->cookie('cms_consent'), true) ?: []);
        if ($request->is('admin*') && $request->user()) {
            $counts = Cache::remember('admin_ui_counts', now()->addMinute(), fn () => [
                'new_leads' => MarketingLead::where('status', 'new')->count(),
                'failed_notifications' => NotificationDelivery::where('status', NotificationDelivery::FAILED)->count(),
            ]);
            View::share('adminUiCounts', $counts);
        }

        return $next($request);
    }

    private function menu(string $location): ?Menu
    {
        return Cache::remember("cms_menu.{$location}", now()->addHour(), fn () => Menu::query()
            ->where('location', $location)
            ->where('is_active', true)
            ->with(['items.page', 'items.children.page'])
            ->first());
    }
}
