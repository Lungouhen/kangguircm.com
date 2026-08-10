@extends('layouts.admin')

@section('title', $post->title)

@section('content')
<div class="p-6">
    <div class="max-w-4xl mx-auto">
        <!-- Header Actions -->
        <div class="mb-6 flex justify-between items-center">
            <a href="{{ route('admin.cms.posts.index') }}"
               class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                ← Back to Posts
            </a>
            <div class="flex space-x-3">
                <a href="{{ route('admin.cms.posts.edit', $post->id) }}"
                   class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    Edit Post
                </a>
                <form action="{{ route('admin.cms.posts.destroy', $post->id) }}" method="POST" class="inline"
                      onsubmit="return confirm('Are you sure you want to delete this post?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                        Delete
                    </button>
                </form>
            </div>
        </div>

        <!-- Post Content -->
        <article class="bg-white rounded-xl shadow-sm border overflow-hidden">
            <!-- Featured Image -->
            @if($post->featured_image)
                <div class="relative h-64 w-full">
                    <img src="{{ asset('storage/' . $post->featured_image) }}"
                         alt="{{ $post->title }}"
                         class="w-full h-full object-cover">
                </div>
            @endif

            <div class="p-8">
                <!-- Title & Meta -->
                <header class="mb-6">
                    <div class="flex items-center space-x-3 mb-3">
                        <span class="px-3 py-1 text-xs font-medium rounded-full
                            {{ $post->status === 'published' ? 'bg-green-100 text-green-800' :
                               ($post->status === 'draft' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                            {{ ucfirst($post->status) }}
                        </span>
                        @if($post->category)
                            <span class="text-sm text-gray-500">in {{ $post->category->name }}</span>
                        @endif
                        <span class="text-sm text-gray-500">• {{ $post->created_at->format('M d, Y') }}</span>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $post->title }}</h1>
                    @if($post->excerpt)
                        <p class="text-lg text-gray-600 italic">{{ $post->excerpt }}</p>
                    @endif
                </header>

                <!-- Content -->
                <div class="prose prose-lg max-w-none mb-8">
                    {!! nl2br(e($post->content)) !!}
                </div>

                <!-- Author & Meta -->
                <footer class="border-t pt-6 mt-8">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                <span class="text-blue-600 font-semibold">
                                    {{ substr($post->author->name ?? 'A', 0, 1) }}
                                </span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $post->author->name ?? 'Unknown' }}</p>
                                <p class="text-xs text-gray-500">Published {{ $post->published_at?->diffForHumans() ?? 'not yet' }}</p>
                            </div>
                        </div>
                        <div class="text-sm text-gray-500">
                            <span>{{ $post->views }} views</span>
                        </div>
                    </div>
                </footer>
            </div>
        </article>

        <!-- SEO Meta Display -->
        @if($post->meta_description)
            <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                <h3 class="text-sm font-medium text-gray-700 mb-2">SEO Meta Description</h3>
                <p class="text-sm text-gray-600">{{ $post->meta_description }}</p>
            </div>
        @endif
    </div>
</div>
@endsection
