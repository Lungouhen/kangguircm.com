@props([
    'title' => config('app.name'),
    'description' => 'KangGui RCM - CMS, Email Marketing & HRM Platform',
    'image' => null,
    'url' => null,
    'type' => 'website',
    'twitterCard' => 'summary_large_image',
])

@php
    $meta = \App\Services\SeoService::generateMeta($title, $description, $image, $url, $type);
@endphp

<!-- Primary Meta Tags -->
<title>{{ $meta['title'] }}</title>
<meta name="title" content="{{ $meta['title'] }}">
<meta name="description" content="{{ $meta['description'] }}">
<meta name="author" content="{{ config('app.name') }}">
<link rel="canonical" href="{{ $meta['url'] }}">

<!-- Open Graph / Facebook -->
<meta property="og:type" content="{{ $meta['type'] }}">
<meta property="og:url" content="{{ $meta['url'] }}">
<meta property="og:title" content="{{ $meta['title'] }}">
<meta property="og:description" content="{{ $meta['description'] }}">
<meta property="og:image" content="{{ $meta['image'] }}">
<meta property="og:site_name" content="{{ $meta['site_name'] }}">

<!-- Twitter -->
<meta property="twitter:card" content="{{ $twitterCard }}">
<meta property="twitter:url" content="{{ $meta['url'] }}">
<meta property="twitter:title" content="{{ $meta['title'] }}">
<meta property="twitter:description" content="{{ $meta['description'] }}">
<meta property="twitter:image" content="{{ $meta['image'] }}">

<!-- Additional SEO -->
<meta name="robots" content="index, follow">
<meta name="googlebot" content="index, follow">
<meta name="viewport" content="width=device-width, initial-scale=1">
