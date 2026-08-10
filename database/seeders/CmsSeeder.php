<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Form;
use App\Models\Menu;
use App\Models\Page;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Database\Seeder;

class CmsSeeder extends Seeder
{
    public function run(): void
    {
        SiteSetting::put('site_name', config('app.name'), 'general');
        SiteSetting::put('site_tagline', 'Specialty-focused revenue cycle management for medical practices.', 'general');
        SiteSetting::put('site_logo', '/images/logo.svg', 'general');
        SiteSetting::put('site_logo_dark', '/images/logo-dark.svg', 'general');
        SiteSetting::put('default_social_image', '/images/og-default.jpg', 'seo');
        SiteSetting::put('default_meta_title', 'Revenue Cycle Management Services for Medical Practices', 'seo');
        SiteSetting::put('default_meta_description', 'Specialty-focused medical billing, coding, denial management, and accounts receivable services that help practices improve collections and financial visibility.', 'seo');
        SiteSetting::put('blog_title', 'Revenue cycle management insights', 'content');
        SiteSetting::put('blog_description', 'Practical guidance for stronger billing operations, fewer denials, and healthier collections.', 'content');
        SiteSetting::put('blog_per_page', 12, 'content');
        SiteSetting::put('analytics_enabled', true, 'analytics');
        SiteSetting::put('analytics_retention_days', 180, 'analytics');
        SiteSetting::put('notifications_email_enabled', true, 'notifications');
        SiteSetting::put('notifications_whatsapp_enabled', false, 'notifications');

        $assessment=Form::query()->firstOrCreate(['slug'=>'free-rcm-assessment'],['name'=>'Free RCM Assessment','purpose'=>'lead','description'=>'Tell us about your practice and revenue-cycle goals.','submit_label'=>'Request my free assessment','success_message'=>'Thank you. An RCM specialist will contact you shortly.','consent_required'=>true,'consent_text'=>'I agree to be contacted. Do not include patient or protected health information.','policy_version'=>'1.0','create_lead'=>true]);
        if ($assessment->fields()->count()===0) $assessment->fields()->createMany([
            ['name'=>'name','label'=>'Name','type'=>'text','is_required'=>true,'sort_order'=>1,'width'=>6],
            ['name'=>'email','label'=>'Work email','type'=>'email','is_required'=>true,'sort_order'=>2,'width'=>6],
            ['name'=>'phone','label'=>'Phone','type'=>'tel','sort_order'=>3,'width'=>6],
            ['name'=>'organization','label'=>'Practice / organization','type'=>'text','sort_order'=>4,'width'=>6],
            ['name'=>'specialty','label'=>'Specialty','type'=>'text','sort_order'=>5,'width'=>6],
            ['name'=>'message','label'=>'Primary RCM challenge','type'=>'textarea','max_length'=>3000,'sort_order'=>6,'width'=>12],
        ]);

        if ($author = User::query()->first()) {
            $home = Page::query()->firstOrCreate(['slug' => 'home'], [
                'author_id' => $author->id,
                'title' => 'Home',
                'meta_title' => 'Revenue Cycle Management Services for Medical Practices',
                'meta_description' => 'Specialty-focused medical billing, coding, denial management, and accounts receivable services that help practices improve collections and financial visibility.',
                'template' => 'full-width',
                'is_published' => true,
                'published_at' => now(),
                'content' => [
                    ['id' => 'home-hero', 'type' => 'hero', 'data' => ['title' => 'Turn more earned revenue into collected revenue', 'subtitle' => 'Medical billing and RCM support designed to reduce denials, shorten accounts receivable cycles, and improve financial visibility.', 'button_text' => 'Request a free RCM assessment', 'button_url' => '#rcm-assessment', 'background_image' => '/images/hero-rcm.webp', 'overlay_color' => '#0b1f3a']],
                    ['id' => 'home-services', 'type' => 'rcm_services', 'data' => []],
                    ['id' => 'home-process', 'type' => 'revenue_cycle', 'data' => []],
                    ['id' => 'home-specialties', 'type' => 'specialty_expertise', 'data' => []],
                    ['id' => 'home-assessment', 'type' => 'form', 'data' => ['form_id'=>$assessment->id,'title'=>'Find the revenue opportunities in your practice','description'=>'Tell us about your revenue cycle. Our team will follow up with practical next steps.']],
                ],
            ]);
            SiteSetting::put('homepage_id', $home->id, 'general');
        }

        $header = Menu::query()->firstOrCreate(['location' => 'header'], ['name' => 'Header Menu', 'is_active' => true]);
        if ($header->items()->count() === 0) {
            $header->items()->createMany([
                ['label' => 'Home', 'type' => 'url', 'url' => '/', 'sort_order' => 1],
                ['label' => 'Services', 'type' => 'url', 'url' => '/#rcm-services', 'sort_order' => 2],
                ['label' => 'Insights', 'type' => 'url', 'url' => '/blog', 'sort_order' => 3],
                ['label' => 'Free Assessment', 'type' => 'url', 'url' => '/#rcm-assessment', 'sort_order' => 4],
            ]);
        }

        Menu::query()->firstOrCreate(['location' => 'footer'], ['name' => 'Footer Menu', 'is_active' => true]);
    }
}
