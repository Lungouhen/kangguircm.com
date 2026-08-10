@extends('layouts.admin')
@section('content')

<form action="{{ route('admin.cms.posts.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Title -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Post Title</label>
                    <input type="text"
                           name="title"
                           id="title"
                           value="{{ old('title') }}"
                           required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('title') border-red-500 @enderror"
                           placeholder="Enter post title">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Slug (Auto-generated) -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">Slug</label>
                    <div class="flex">
                        <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                            {{ url('/') }}/blog/
                        </span>
                        <input type="text"
                               name="slug"
                               id="slug"
                               value="{{ old('slug') }}"
                               required
                               class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md focus:ring-blue-500 focus:border-blue-500 sm:text-sm border-gray-300 @error('slug') border-red-500 @enderror"
                               placeholder="auto-generated-slug">
                    </div>
                    @error('slug')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-xs text-gray-500">Leave blank to auto-generate from title</p>
                </div>

                <!-- Content -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Content</label>
                    <textarea name="content"
                              id="content"
                              rows="12"
                              required
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('content') border-red-500 @enderror"
                              placeholder="Write your post content here...">{{ old('content') }}</textarea>
                    @error('content')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-xs text-gray-500">Supports WordPress-style blocks: <!-- wp:paragraph --></p>
                </div>

                <!-- Excerpt -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <label for="excerpt" class="block text-sm font-medium text-gray-700 mb-2">Excerpt</label>
                    <textarea name="excerpt"
                              id="excerpt"
                              rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('excerpt') border-red-500 @enderror"
                              placeholder="Brief summary of the post (optional)">{{ old('excerpt') }}</textarea>
                    @error('excerpt')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Publish Box -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Publish</h3>

                    <div class="space-y-4">
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select name="status"
                                    id="status"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Published</option>
                                <option value="scheduled" {{ old('status') === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                            </select>
                        </div>

                        <div id="publish_at_container" {{ old('status') !== 'scheduled' ? 'style="display:none;"' : '' }}>
                            <label for="publish_at" class="block text-sm font-medium text-gray-700 mb-2">Publish At</label>
                            <input type="datetime-local"
                                   name="publish_at"
                                   id="publish_at"
                                   value="{{ old('publish_at') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <button type="submit"
                                name="action"
                                value="save"
                                class="w-full btn-primary inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Save Post
                        </button>

                        <button type="submit"
                                name="action"
                                value="publish"
                                class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            Publish Now
                        </button>
                    </div>
                </div>

                <!-- Featured Image -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Featured Image</h3>

                    <div id="image-preview" class="mb-4">
                        @if(old('featured_image'))
                            <img src="{{ Storage::url(old('featured_image')) }}" alt="Preview" class="w-full h-48 object-cover rounded-lg">
                        @endif
                    </div>

                    <input type="file"
                           name="featured_image"
                           id="featured_image"
                           accept="image/*"
                           class="hidden"
                           onchange="previewImage(this)">

                    <label for="featured_image"
                           class="cursor-pointer block w-full border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-gray-400 transition-colors">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <p class="mt-1 text-sm text-gray-600">Click to upload image</p>
                        <p class="text-xs text-gray-500">PNG, JPG, GIF up to 5MB</p>
                    </label>
                    @error('featured_image')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Categories -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Categories</h3>

                    <div class="space-y-2 max-h-48 overflow-y-auto">
                        @foreach($categories as $category)
                            <label class="flex items-center">
                                <input type="checkbox"
                                       name="categories[]"
                                       value="{{ $category->id }}"
                                       {{ in_array($category->id, old('categories', [])) ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <span class="ml-2 text-sm text-gray-700">{{ $category->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('categories')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- SEO Meta -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">SEO Settings</h3>

                    <div class="space-y-4">
                        <div>
                            <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-2">Meta Title</label>
                            <input type="text"
                                   name="meta_title"
                                   id="meta_title"
                                   value="{{ old('meta_title') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="SEO title (defaults to post title)">
                        </div>

                        <div>
                            <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
                            <textarea name="meta_description"
                                      id="meta_description"
                                      rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                      placeholder="SEO description (defaults to excerpt)">{{ old('meta_description') }}</textarea>
                        </div>

                        <div>
                            <label for="meta_keywords" class="block text-sm font-medium text-gray-700 mb-2">Meta Keywords</label>
                            <input type="text"
                                   name="meta_keywords"
                                   id="meta_keywords"
                                   value="{{ old('meta_keywords') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="keyword1, keyword2, keyword3">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        // Auto-generate slug from title
        document.getElementById('title').addEventListener('input', function() {
            const title = this.value;
            const slugInput = document.getElementById('slug');

            // Only auto-generate if slug is empty or was previously auto-generated
            if (!slugInput.value || slugInput.dataset.autoGenerated === 'true') {
                slugInput.value = title
                    .toLowerCase()
                    .replace(/[^a-z0-9]+/g, '-')
                    .replace(/(^-|-$)/g, '');
                slugInput.dataset.autoGenerated = 'true';
            }
        });

        // Toggle publish date field
        document.getElementById('status').addEventListener('change', function() {
            const container = document.getElementById('publish_at_container');
            container.style.display = this.value === 'scheduled' ? 'block' : 'none';
        });

        // Image preview
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('image-preview');
                    const image = document.createElement('img');
                    image.src = String(e.target.result);
                    image.alt = 'Selected featured image preview';
                    image.className = 'w-full h-48 object-cover rounded-lg';
                    image.width = 800;
                    image.height = 400;
                    preview.replaceChildren(image);
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Form submission handling
        document.querySelectorAll('button[name="action"]').forEach(button => {
            button.addEventListener('click', function() {
                if (this.value === 'publish') {
                    document.getElementById('status').value = 'published';
                }
            });
        });
    </script>
@endsection
