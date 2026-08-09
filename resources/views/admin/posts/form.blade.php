@extends('layouts.admin')

@section('title', isset($post) ? 'Edit Post' : 'Create Post')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <h2 class="text-2xl font-semibold text-gray-900 mb-6">
            {{ isset($post) ? 'Edit Post' : 'Create New Post' }}
        </h2>

        <form action="{{ isset($post) ? route('admin.posts.update', $post) : route('admin.posts.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @if(isset($post)) @method('PUT') @endif

            <!-- Title -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Title *</label>
                <input type="text" name="title" value="{{ old('title', $post->title ?? '') }}" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm border p-2">
            </div>

            <!-- Slug -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Slug</label>
                <input type="text" name="slug" value="{{ old('slug', $post->slug ?? '') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm border p-2">
                <p class="text-xs text-gray-500 mt-1">Leave empty to auto-generate from title.</p>
            </div>

            <!-- Excerpt -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Excerpt</label>
                <textarea name="excerpt" rows="3"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm border p-2">{{ old('excerpt', $post->excerpt ?? '') }}</textarea>
            </div>

            <!-- Content -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Content *</label>
                <textarea name="content" id="content" rows="12" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm border p-2">{{ old('content', $post->content ?? '') }}</textarea>
            </div>

            <!-- Featured Image -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Featured Image</label>
                @if(isset($post) && $post->featured_image)
                    <img src="{{ asset('storage/' . $post->featured_image) }}" class="w-32 h-32 object-cover rounded mb-2">
                @endif
                <input type="file" name="featured_image" accept="image/*"
                    class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            </div>

            <!-- Status & Publish Date -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Status *</label>
                    <select name="status" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm border p-2">
                        <option value="draft" {{ old('status', $post->status ?? 'draft') === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status', $post->status ?? '') === 'published' ? 'selected' : '' }}>Published</option>
                        <option value="scheduled" {{ old('status', $post->status ?? '') === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Publish Date</label>
                    <input type="datetime-local" name="published_at" value="{{ old('published_at', $post->published_at?->format('Y-m-d\TH:i') ?? '') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm border p-2">
                </div>
            </div>

            <!-- Categories & Tags -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Categories</label>
                    <select name="category_ids[]" multiple
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm border p-2 h-32">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" 
                                {{ in_array($category->id, old('category_ids', $post?->categories->pluck('id')->toArray() ?? [])) ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tags</label>
                    <select name="tag_ids[]" multiple
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm border p-2 h-32">
                        @foreach($tags as $tag)
                            <option value="{{ $tag->id }}"
                                {{ in_array($tag->id, old('tag_ids', $post?->tags->pluck('id')->toArray() ?? [])) ? 'selected' : '' }}>
                                {{ $tag->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- SEO Meta -->
            <div class="border-t pt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">SEO Settings</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Meta Title (50-60 chars)</label>
                        <input type="text" name="meta_title" value="{{ old('meta_title', $post->meta_title ?? '') }}" maxlength="60"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm border p-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Meta Description (150-160 chars)</label>
                        <textarea name="meta_description" rows="2" maxlength="160"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm border p-2">{{ old('meta_description', $post->meta_description ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-3 pt-6">
                <a href="{{ route('admin.posts.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-blue-700">
                    {{ isset($post) ? 'Update Post' : 'Create Post' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
