<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="{{ $theme['primary_color']??'#175cd3' }}">
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="icon" href="{{ asset('favicon-32x32.png') }}" sizes="32x32" type="image/png">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">
    @hasSection('seo') @yield('seo') @else <x-seo-meta :title="$site['default_meta_title'] ?? null" :description="$site['default_meta_description'] ?? ''" :image="$site['default_social_image'] ?? null" /> @endif
    <x-integration-tags />
    @php $font=$theme['font_family']??'Plus Jakarta Sans'; @endphp
    <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family={{ str_replace(' ','+',$font) }}:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css','resources/js/app.js'])
    <style>
        :root{--primary-color:{{ $theme['primary_color']??'#2563EB' }};--secondary-color:{{ $theme['secondary_color']??'#0891B2' }};--font-family:'{{ $font }}',sans-serif;--animation-speed:{{ $theme['animation_speed']??0.6 }}s}
        body{font-family:var(--font-family)}
        .header-glass{@if(($theme['header_style']??'glass')==='glass') backdrop-filter:blur(10px);background:rgb(255 255 255/.88);@elseif(($theme['header_style']??'')==='solid') background:#fff;@else background:transparent;@endif}
        .container-custom{@if(($theme['layout_width']??'boxed')==='boxed') max-width:1280px;margin-inline:auto;@else max-width:100%;@endif}
        {!! str_replace(['</style','<script','</script'], '', (string)($site['custom_css']??'')) !!}
    </style>
</head>
<body class="bg-gray-50 text-gray-900 antialiased">
<a href="#main-content" class="skip-link">Skip to content</a>
@if(!($hideChrome??false))
<header class="fixed w-full top-0 z-50 transition-all duration-300 header-glass shadow-sm"><nav class="container-custom px-6 py-4" aria-label="Primary navigation"><div class="flex justify-between items-center">
<a href="{{ route('home') }}" class="flex items-center gap-3 font-bold text-xl" style="color:var(--primary-color)"><img src="{{ $site['logo'] }}" alt="{{ $site['name'] }}" width="180" height="48" class="max-h-12 w-auto"><span class="sr-only">{{ $site['name'] }}</span></a>
<div class="hidden md:flex items-center gap-8">@if($headerMenu)<x-public-menu :items="$headerMenu->items" />@else<a href="{{ route('home') }}">Home</a><a href="{{ route('posts.index') }}">Insights</a>@endif</div>
<details class="relative md:hidden"><summary class="cursor-pointer rounded border px-3 py-2" aria-label="Open navigation menu">Menu</summary><nav class="absolute right-0 mt-2 w-56 rounded-lg bg-white p-3 shadow-xl" aria-label="Mobile navigation">@if($headerMenu)<x-public-menu :items="$headerMenu->items" :mobile="true" />@else<a href="{{ route('home') }}" class="block p-2">Home</a><a href="{{ route('posts.index') }}" class="block p-2">Insights</a>@endif</nav></details>
</div></nav></header>
@endif
<main id="main-content" class="theme-surface {{ ($hideChrome??false)?'':'pt-20' }}" data-template="{{ isset($page)?$page->template:'site' }}">@yield('content')</main>
@if(!($hideChrome??false))<footer class="bg-slate-950 text-white py-14 mt-20"><div class="container-custom px-6 grid md:grid-cols-3 gap-10"><div><img src="{{ $site['logo_dark']?:$site['logo'] }}" alt="{{ $site['name'] }}" width="180" height="48" class="max-h-12 w-auto"><p class="mt-4 text-slate-300">{{ $site['footer_text']?:$site['tagline'] }}</p></div><div><h2 class="font-bold text-lg">Contact</h2>@if($site['email'])<a class="block mt-3 text-slate-300" href="mailto:{{ $site['email'] }}">{{ $site['email'] }}</a>@endif @if($site['phone'])<a class="block mt-2 text-slate-300" href="tel:{{ preg_replace('/[^+0-9]/','',$site['phone']) }}">{{ $site['phone'] }}</a>@endif @if($site['address'])<address class="mt-2 text-slate-300 not-italic">{{ $site['address'] }}</address>@endif</div><nav aria-label="Footer navigation"><h2 class="font-bold text-lg">Explore</h2><div class="mt-3 space-y-2">@if($footerMenu)<x-public-menu :items="$footerMenu->items" :mobile="true" />@endif</div></nav></div><div class="container-custom px-6 mt-10 pt-6 border-t border-slate-800 text-sm text-slate-400 flex flex-wrap justify-between gap-4"><span>{{ $site['copyright']?:'© '.date('Y').' '.$site['name'].'. All rights reserved.' }}</span><nav class="flex flex-wrap gap-4" aria-label="Legal policies">@foreach($legalPolicies??[] as $policy)<a href="{{ route('legal.show',$policy->slug) }}">{{ $policy->title }}</a>@endforeach</nav></div></footer>@endif
<x-consent-banner />
</body></html>
