@extends('layouts.app')
@section('seo')
<x-seo-meta
    :title="$post->meta_title ?: $post->title"
    :description="$post->meta_description ?: ($post->excerpt ?: str(strip_tags($post->content))->limit(160))"
    :image="$post->featured_image ? asset('storage/'.$post->featured_image) : null"
    :url="$post->canonical_url ?: request()->url()"
    type="article"
/>
@if($post->noindex)<meta name="robots" content="noindex,nofollow">@endif
<x-structured-data :data="\App\Services\SeoService::articleSchema($post)" />
@endsection
@section('content')
<article class="py-16">
    <div class="max-w-3xl mx-auto px-6">
        <header>
            <p class="text-sm font-semibold text-blue-600"><time datetime="{{ $post->published_at?->toDateString() }}">{{ $post->published_at?->format('F j, Y') }}</time></p>
            <h1 class="mt-3 text-4xl md:text-5xl font-bold">{{ $post->title }}</h1>
        </header>
        @if($post->featured_image)
            <x-responsive-image :src="asset('storage/'.$post->featured_image)" :alt="'Featured image for '.$post->title" width="1200" height="630" sizes="(min-width: 768px) 768px, 100vw" :priority="true" class="mt-8 rounded-2xl w-full" />
        @endif
        <div class="mt-10 prose prose-slate max-w-none">{!! nl2br(e($post->content)) !!}</div>
    </div>
</article>
@endsection
