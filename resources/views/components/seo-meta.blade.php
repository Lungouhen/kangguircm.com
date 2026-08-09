@props(['title' => config('app.name'), 'description' => '', 'image' => null, 'url' => request()->url(), 'type' => 'website'])

{{-- Title --}}
<title>{{ $title }} | {{ config('app.name') }}</title>
<meta name="title" content="{{ $title }} | {{ config('app.name') }}">

{{-- Meta Description --}}
@if($description)
<meta name="description" content="{{ $description }}">
@endif

{{-- Canonical URL --}}
<link rel="canonical" href="{{ $url }}">

{{-- Open Graph / Facebook --}}
<meta property="og:type" content="{{ $type }}">
<meta property="og:url" content="{{ $url }}">
<meta property="og:title" content="{{ $title }} | {{ config('app.name') }}">
@if($description)
<meta property="og:description" content="{{ $description }}">
@endif
@if($image)
<meta property="og:image" content="{{ $image }}">
@endif

{{-- Twitter --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:url" content="{{ $url }}">
<meta name="twitter:title" content="{{ $title }} | {{ config('app.name') }}">
@if($description)
<meta name="twitter:description" content="{{ $description }}">
@endif
@if($image)
<meta name="twitter:image" content="{{ $image }}">
@endif
