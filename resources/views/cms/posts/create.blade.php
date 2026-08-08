@extends('layouts.admin')

@section('title', 'Create Post')

@section('content')
<div class="page-header">
    <div class="page-header__title-section">
        <h1 class="page-header__title">Create New Post</h1>
        <p class="page-header__subtitle">Add a new blog post</p>
    </div>
    <div class="page-header__actions">
        <a href="{{ route('admin.posts.index') }}" class="btn btn--outline">
            ← Back to Posts
        </a>
    </div>
</div>

<div class="card">
    <div class="card__body">
        <form action="{{ route('admin.posts.store') }}" 
              method="POST" 
              enctype="multipart/form-data"
              class="form">
            @csrf
            
            <div class="form__group">
                <label for="title" class="form__label">Title <span class="form__required">*</span></label>
                <input type="text" 
                       id="title" 
                       name="title" 
                       value="{{ old('title') }}"
                       class="form__input @error('title') form__input--error @enderror"
                       required
                       autofocus>
                @error('title')
                    <span class="form__error">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form__group">
                <label for="slug" class="form__label">Slug <span class="form__required">*</span></label>
                <input type="text" 
                       id="slug" 
                       name="slug" 
                       value="{{ old('slug') }}"
                       class="form__input @error('slug') form__input--error @enderror"
                       required>
                <small class="form__help">URL-friendly name (e.g., my-awesome-post)</small>
                @error('slug')
                    <span class="form__error">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form__group">
                <label for="excerpt" class="form__label">Excerpt</label>
                <textarea id="excerpt" 
                          name="excerpt" 
                          rows="3"
                          class="form__textarea @error('excerpt') form__textarea--error @enderror">{{ old('excerpt') }}</textarea>
                <small class="form__help">Brief summary of the post (max 500 characters)</small>
                @error('excerpt')
                    <span class="form__error">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form__group">
                <label for="content" class="form__label">Content <span class="form__required">*</span></label>
                <textarea id="content" 
                          name="content" 
                          rows="15"
                          class="form__textarea @error('content') form__textarea--error @enderror"
                          required>{{ old('content') }}</textarea>
                @error('content')
                    <span class="form__error">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form__row">
                <div class="form__group form__group--half">
                    <label for="category_id" class="form__label">Category</label>
                    <select id="category_id" 
                            name="category_id" 
                            class="form__select @error('category_id') form__select--error @enderror">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <span class="form__error">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form__group form__group--half">
                    <label for="status" class="form__label">Status <span class="form__required">*</span></label>
                    <select id="status" 
                            name="status" 
                            class="form__select @error('status') form__select--error @enderror"
                            required>
                        <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Published</option>
                        <option value="archived" {{ old('status') === 'archived' ? 'selected' : '' }}>Archived</option>
                    </select>
                    @error('status')
                        <span class="form__error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            
            <div class="form__group">
                <label for="featured_image" class="form__label">Featured Image</label>
                <input type="file" 
                       id="featured_image" 
                       name="featured_image" 
                       accept="image/*"
                       class="form__file @error('featured_image') form__file--error @enderror">
                <small class="form__help">JPEG, PNG, JPG, GIF (max 2MB)</small>
                @error('featured_image')
                    <span class="form__error">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form__group">
                <label for="meta_title" class="form__label">Meta Title</label>
                <input type="text" 
                       id="meta_title" 
                       name="meta_title" 
                       value="{{ old('meta_title') }}"
                       class="form__input @error('meta_title') form__input--error @enderror"
                       maxlength="60">
                <small class="form__help">SEO meta title (max 60 characters)</small>
                @error('meta_title')
                    <span class="form__error">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form__group">
                <label for="meta_description" class="form__label">Meta Description</label>
                <textarea id="meta_description" 
                          name="meta_description" 
                          rows="2"
                          class="form__textarea @error('meta_description') form__textarea--error @enderror"
                          maxlength="160">{{ old('meta_description') }}</textarea>
                <small class="form__help">SEO meta description (max 160 characters)</small>
                @error('meta_description')
                    <span class="form__error">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form__actions">
                <button type="submit" class="btn btn--primary">
                    Create Post
                </button>
                <a href="{{ route('admin.posts.index') }}" class="btn btn--outline">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
// Auto-generate slug from title
document.getElementById('title').addEventListener('input', function(e) {
    const slugInput = document.getElementById('slug');
    if (!slugInput.value || slugInput.value === '') {
        const slug = e.target.value
            .toLowerCase()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/(^-|-$)/g, '');
        slugInput.value = slug;
    }
});
</script>
@endpush
@endsection
