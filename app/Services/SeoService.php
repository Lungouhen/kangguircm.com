<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Post;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\URL;

class SeoService
{
    /**
     * Generate meta tags for a page
     */
    public static function generateMeta(
        string $title,
        string $description,
        ?string $image = null,
        ?string $url = null,
        string $type = 'website'
    ): array {
        $defaults = [
            'title' => config('app.name'),
            'description' => 'Revenue cycle management services for medical practices, including billing, coding, denial management, and accounts receivable support.',
            'image' => SiteSetting::valueOf('default_social_image', asset('images/og-default.jpg')),
            'url' => URL::current(),
            'type' => $type,
        ];

        return [
            'title' => $title ?: $defaults['title'],
            'description' => $description ?: $defaults['description'],
            'image' => $image ?: $defaults['image'],
            'url' => $url ?: $defaults['url'],
            'type' => $type,
            'site_name' => SiteSetting::valueOf('site_name', config('app.name')),
            'twitter_card' => 'summary_large_image',
        ];
    }

    /**
     * Generate JSON-LD structured data for an article
     */
    public static function articleSchema(Post $post): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $post->title,
            'description' => $post->excerpt ?? substr(strip_tags($post->content), 0, 160),
            'image' => $post->featured_image ? asset('storage/'.$post->featured_image) : SiteSetting::valueOf('default_social_image', asset('images/og-default.jpg')),
            'datePublished' => $post->published_at?->toIso8601String() ?? $post->created_at->toIso8601String(),
            'dateModified' => $post->updated_at->toIso8601String(),
            'author' => [
                '@type' => 'Person',
                'name' => $post->author->name ?? 'Admin',
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => SiteSetting::valueOf('site_name', config('app.name')),
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => SiteSetting::valueOf('site_logo', asset('images/logo.svg')),
                ],
            ],
        ];
    }

    /**
     * Generate organization schema
     */
    public static function organizationSchema(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => SiteSetting::valueOf('site_name', config('app.name')),
            'url' => config('app.url'),
            'logo' => SiteSetting::valueOf('site_logo', asset('images/logo.svg')),
        ];
    }

    /**
     * Generate breadcrumb schema
     */
    public static function breadcrumbSchema(array $items): array
    {
        $itemList = [];
        foreach ($items as $index => $item) {
            $itemList[] = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $item['name'],
                'item' => $item['url'],
            ];
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $itemList,
        ];
    }
}
