<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SiteSettingController extends Controller
{
    public function edit(): View
    {
        return view('admin.settings.site', [
            'pages' => Page::query()->published()->orderBy('title')->get(['id', 'title', 'is_published']),
            'settings' => collect([
                'site_name','site_tagline','site_logo','site_logo_dark','homepage_id','contact_email','contact_phone',
                'contact_address','footer_text','copyright_text','social_facebook','social_linkedin','social_youtube',
                'default_meta_title','default_meta_description','default_social_image','blog_title','blog_description','blog_per_page','analytics_enabled','analytics_retention_days','notifications_email_enabled','notifications_whatsapp_enabled','custom_css',
            ])->mapWithKeys(fn ($key) => [$key => SiteSetting::valueOf($key)]),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $request->merge([
            'analytics_enabled'=>$request->boolean('analytics_enabled'),
            'notifications_email_enabled'=>$request->boolean('notifications_email_enabled'),
            'notifications_whatsapp_enabled'=>$request->boolean('notifications_whatsapp_enabled'),
        ]);
        $data = $request->validate([
            'site_name' => ['required', 'string', 'max:120'],
            'site_tagline' => ['nullable', 'string', 'max:180'],
            'site_logo' => ['nullable', 'string', 'max:500'],
            'site_logo_dark' => ['nullable', 'string', 'max:500'],
            'homepage_id' => ['nullable', Rule::exists('pages', 'id')],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:40'],
            'contact_address' => ['nullable', 'string', 'max:500'],
            'footer_text' => ['nullable', 'string', 'max:1000'],
            'copyright_text' => ['nullable', 'string', 'max:255'],
            'social_facebook' => ['nullable', 'url', 'max:500'],
            'social_linkedin' => ['nullable', 'url', 'max:500'],
            'social_youtube' => ['nullable', 'url', 'max:500'],
            'default_meta_title' => ['nullable', 'string', 'max:60'],
            'default_meta_description' => ['nullable', 'string', 'max:160'],
            'default_social_image' => ['nullable', 'string', 'max:500'],
            'blog_title' => ['nullable', 'string', 'max:120'],
            'blog_description' => ['nullable', 'string', 'max:500'],
            'blog_per_page' => ['nullable', 'integer', 'min:3', 'max:48'],
            'analytics_enabled' => ['nullable', 'boolean'],
            'analytics_retention_days' => ['nullable', 'integer', 'min:30', 'max:730'],
            'notifications_email_enabled' => ['nullable', 'boolean'],
            'notifications_whatsapp_enabled' => ['nullable', 'boolean'],
            'custom_css' => ['nullable', 'string', 'max:50000'],
        ]);

        foreach ($data as $key => $value) {
            SiteSetting::put($key, $value, match (true) {
                str_starts_with($key, 'contact_'), str_starts_with($key, 'social_') => 'contact',
                str_starts_with($key, 'default_') => 'seo',
                $key === 'custom_css' => 'appearance',
                default => 'general',
            });
        }

        return back()->with('success', 'Site settings updated.');
    }
}
