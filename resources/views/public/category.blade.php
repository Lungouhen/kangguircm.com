@extends('layouts.app')
@section('seo')
<x-seo-meta :title="$category->name" :description="$category->description ?: 'Read the latest '.strtolower($category->name).' insights from '.config('app.name').'.'" :url="request()->url()" />
@endsection
@section('content')
<section class="py-16" aria-labelledby="category-heading">
    <div class="max-w-6xl mx-auto px-6">
        <header><h1 id="category-heading" class="text-4xl font-bold">{{ $category->name }}</h1>@if($category->description)<p class="mt-4 text-lg text-slate-600">{{ $category->description }}</p>@endif</header>
        <div class="mt-10 grid md:grid-cols-3 gap-6">
            @forelse($posts as $post)
                <article class="bg-white border rounded-xl p-6"><h2 class="text-xl font-bold"><a href="{{ route('post.show',$post->slug) }}">{{ $post->title }}</a></h2><p class="mt-3 text-slate-600">{{ $post->excerpt }}</p></article>
            @empty
                <p>No published posts in this category.</p>
            @endforelse
        </div>
        <nav class="mt-8" aria-label="Category pagination">{{ $posts->links() }}</nav>
    </div>
</section>
@endsection
