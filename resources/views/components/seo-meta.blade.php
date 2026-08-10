@props([
    'title' => null,
    'description' => 'Specialty-focused revenue cycle management services that help medical practices reduce denials, accelerate collections, and improve financial visibility.',
    'image' => null,
    'url' => null,
    'type' => 'website',
])
@php
    $siteName = $site['name'] ?? config('app.name');
    $title = $title ?: ($site['default_meta_title'] ?? null);
    $description = $description ?: ($site['default_meta_description'] ?? '');
    $image = $image ?: ($site['default_social_image'] ?? null);
    $resolvedTitle = trim((string) ($title ?: $siteName));
    $fullTitle = $resolvedTitle === $siteName ? $siteName : $resolvedTitle.' | '.$siteName;
    $canonical = $url ?: request()->url();
    $socialImage = $image && !str_starts_with($image, 'http') ? asset(ltrim($image, '/')) : $image;
@endphp
<title>{{ $fullTitle }}</title>
<meta name="title" content="{{ $fullTitle }}">
@if($description)<meta name="description" content="{{ $description }}">@endif
<link rel="canonical" href="{{ $canonical }}">
<meta property="og:site_name" content="{{ $siteName }}">
<meta property="og:type" content="{{ $type }}">
<meta property="og:url" content="{{ $canonical }}">
<meta property="og:title" content="{{ $fullTitle }}">
@if($description)<meta property="og:description" content="{{ $description }}">@endif
@if($socialImage)
<meta property="og:image" content="{{ $socialImage }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
@endif
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $fullTitle }}">
@if($description)<meta name="twitter:description" content="{{ $description }}">@endif
@if($socialImage)<meta name="twitter:image" content="{{ $socialImage }}">@endif
