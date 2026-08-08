@extends('layouts.admin')

@section('title', 'Edit Post')

@section('content')
<div class="p-6">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit Post</h1>
                <p class="text-sm text-gray-500 mt-1">Update your blog post or page content</p>
            </div>
            <a href="{{ route('admin.posts.index') }}" 
               class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                ← Back to Posts
            </a>
        </div>

        <!-- Form -->
        <form action="{{ route('admin.posts.update', $post->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Title & Slug -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-2">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Post Title *</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $post->title) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('title') border-red-500 @enderror">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">Slug *</label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug', $post->slug) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('slug') border-red-500 @enderror">
                    @error('slug')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Content -->
            <div>
                <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Content *</label>
                <textarea name="content" id="content" rows="12" required
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('content') border-red-500 @enderror">{{ old('content', $post->content) }}</textarea>
                @error('content')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Excerpt -->
            <div>
                <label for="excerpt" class="block text-sm font-medium text-gray-700 mb-2">Excerpt</label>
                <textarea name="excerpt" id="excerpt" rows="3" maxlength="500"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('excerpt') border-red-500 @enderror">{{ old('excerpt', $post->excerpt) }}</textarea>
                <p class="mt-1 text-xs text-gray-500">Brief summary (max 500 characters)</p>
                @error('excerpt')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Category & Status -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                    <select name="category_id" id="category_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('category_id') border-red-500 @enderror">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $post->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                    <select name="status" id="status" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('status') border-red-500 @enderror">
                        <option value="draft" {{ old('status', $post->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status', $post->status) === 'published' ? 'selected' : '' }}>Published</option>
                        <option value="archived" {{ old('status', $post->status) === 'archived' ? 'selected' : '' }}>Archived</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Featured Image -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Featured Image</label>
                @if($post->featured_image)
                    <div class="mb-3">
                        <img src="{{ asset('storage/' . $post->featured_image) }}" alt="Current image" class="h-32 w-auto object-cover rounded-lg">
                        <p class="text-xs text-gray-500 mt-1">Current image. Upload new to replace.</p>
                    </div>
                @endif
                <input type="file" name="featured_image" accept="image/*"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('featured_image') border-red-500 @enderror">
                @error('featured_image')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- SEO Meta -->
            <div class="border-t pt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">SEO Settings</h3>
                <div class="space-y-4">
                    <div>
                        <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
                        <textarea name="meta_description" id="meta_description" rows="2" maxlength="160"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('meta_description') border-red-500 @enderror">{{ old('meta_description', $post->meta_description) }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">Recommended for search engines (max 160 characters)</p>
                        @error('meta_description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="published_at" class="block text-sm font-medium text-gray-700 mb-2">Publish Date</label>
                        <input type="datetime-local" name="published_at" id="published_at" value="{{ old('published_at', $post->published_at?->format('Y-m-d\TH:i')) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('published_at') border-red-500 @enderror">
                        @error('published_at')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-4 pt-6 border-t">
                <a href="{{ route('admin.posts.index') }}" 
                   class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    Update Post
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
// Auto-generate slug from title
document.getElementById('title').addEventListener('input', function() {
    const title = this.value;
    const slugInput = document.getElementById('slug');
    
    // Only auto-generate if slug hasn't been manually edited
    if (!slugInput.dataset.manuallyEdited) {
        slugInput.value = title.toLowerCase()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/(^-|-$)/g, '');
    }
});

// Track manual slug edits
document.getElementById('slug').addEventListener('input', function() {
    this.dataset.manuallyEdited = 'true';
});
</script>
@endpush
@endsection
