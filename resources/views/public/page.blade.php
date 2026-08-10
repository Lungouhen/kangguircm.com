@php($hideChrome = $page->template === 'landing')
@extends('layouts.app')
@section('seo')
<x-seo-meta :title="$page->meta_title ?: $page->title" :description="$page->meta_description ?: ''" :image="$page->social_image" :url="$page->canonical_url ?: request()->url()" />
@if($page->noindex || ($previewMode??false))<meta name="robots" content="noindex,nofollow">@endif
<script type="application/ld+json">{!! json_encode(['@context'=>'https://schema.org','@type'=>$page->schema_type ?: 'WebPage','name'=>$page->title,'url'=>$page->canonical_url ?: request()->url(),'description'=>$page->meta_description], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT) !!}</script>
@endsection
@section('content')
@if($previewMode??false)<div class="fixed bottom-4 left-4 z-50 rounded bg-amber-500 px-4 py-2 font-bold text-slate-950 shadow-xl">Draft preview</div>@endif
@if(!$hasHero)<h1 class="sr-only">{{ $page->title }}</h1>@endif
{!! $renderedContent !!}
@endsection
