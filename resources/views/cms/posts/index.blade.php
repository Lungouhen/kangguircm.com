@extends('layouts.admin')

@section('title', 'Blog Posts')

@section('content')
<div class="page-header">
    <div class="page-header__title-section">
        <h1 class="page-header__title">Blog Posts</h1>
        <p class="page-header__subtitle">Manage your blog content</p>
    </div>
    <div class="page-header__actions">
        <a href="{{ route('admin.cms.posts.create') }}" class="btn btn--primary">
            <span class="btn__icon">+</span>
            New Post
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert--success">
        {{ session('success') }}
    </div>
@endif

<div class="card">
    <div class="card__header">
        <h2 class="card__title">All Posts</h2>
    </div>
    <div class="card__body">
        <div class="table-responsive">
            <table class="table">
                <thead class="table__head">
                    <tr>
                        <th class="table__cell">Title</th>
                        <th class="table__cell">Author</th>
                        <th class="table__cell">Category</th>
                        <th class="table__cell">Status</th>
                        <th class="table__cell">Published</th>
                        <th class="table__cell">Views</th>
                        <th class="table__cell">Actions</th>
                    </tr>
                </thead>
                <tbody class="table__body">
                    @forelse($posts as $post)
                        <tr class="table__row">
                            <td class="table__cell">
                                <strong>{{ $post->title }}</strong>
                            </td>
                            <td class="table__cell">
                                {{ $post->author->name ?? 'N/A' }}
                            </td>
                            <td class="table__cell">
                                {{ $post->category->name ?? 'Uncategorized' }}
                            </td>
                            <td class="table__cell">
                                <span class="badge badge--{{ $post->status }}">
                                    {{ ucfirst($post->status) }}
                                </span>
                            </td>
                            <td class="table__cell">
                                {{ $post->published_at?->format('M d, Y') ?? '-' }}
                            </td>
                            <td class="table__cell">
                                {{ $post->views }}
                            </td>
                            <td class="table__cell">
                                <div class="action-buttons">
                                    <a href="{{ route('admin.cms.posts.show', $post->id) }}"
                                       class="btn btn--sm btn--outline"
                                       title="View">
                                        👁
                                    </a>
                                    <a href="{{ route('admin.cms.posts.edit', $post->id) }}"
                                       class="btn btn--sm btn--outline"
                                       title="Edit">
                                        ✏️
                                    </a>
                                    @if($post->status === 'draft')
                                        <form action="{{ route('admin.cms.posts.publish', $post->id) }}"
                                              method="POST"
                                              style="display: inline;">
                                            @csrf
                                            <button type="submit"
                                                    class="btn btn--sm btn--success"
                                                    title="Publish">
                                                🚀
                                            </button>
                                        </form>
                                    @endif
                                    <form action="{{ route('admin.cms.posts.destroy', $post->id) }}"
                                          method="POST"
                                          style="display: inline;"
                                          onsubmit="return confirm('Are you sure you want to delete this post?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn btn--sm btn--danger"
                                                title="Delete">
                                            🗑️
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="table__cell table__cell--center">
                                No posts found. <a href="{{ route('admin.cms.posts.create') }}">Create your first post</a>.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-container">
            {{ $posts->links() }}
        </div>
    </div>
</div>
@endsection
