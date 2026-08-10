@extends('layouts.app')
@section('seo')
<x-seo-meta :title="$blogTitle" :description="$blogDescription" :url="route('posts.index')" />
@endsection
@section('content')
<section class="py-16" aria-labelledby="insights-heading">
    <div class="max-w-6xl mx-auto px-6">
        <header class="max-w-3xl"><h1 id="insights-heading" class="text-4xl font-bold">{{ $blogTitle }}</h1><p class="mt-4 text-lg text-slate-600">{{ $blogDescription }}</p></header>
        <div class="mt-10 grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($posts as $post)
                <article class="rounded-xl border border-slate-200 bg-white p-6"><p class="text-sm text-blue-600"><time datetime="{{ $post->published_at?->toDateString() }}">{{ $post->published_at?->format('F j, Y') }}</time></p><h2 class="mt-2 text-xl font-bold"><a href="{{ route('post.show',$post->slug) }}">{{ $post->title }}</a></h2><p class="mt-3 text-slate-600">{{ $post->excerpt }}</p><a class="mt-5 inline-flex font-semibold text-blue-700" href="{{ route('post.show',$post->slug) }}">Read {{ $post->title }}</a></article>
            @empty
                <p>No insights have been published yet.</p>
            @endforelse
        </div>
        <nav class="mt-8" aria-label="Blog pagination">{{ $posts->links() }}</nav>
    </div>
</section>
@endsection
