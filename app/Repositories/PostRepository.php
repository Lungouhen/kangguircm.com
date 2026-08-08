<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class PostRepository
{
    public function __construct(
        private Post $model
    ) {}

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model
            ->with(['author', 'category'])
            ->latest()
            ->paginate($perPage);
    }

    public function find(int $id): ?Post
    {
        return $this->model->with(['author', 'category'])->find($id);
    }

    public function create(array $data): Post
    {
        return $this->model->create([
            'title' => $data['title'],
            'slug' => $data['slug'],
            'excerpt' => $data['excerpt'] ?? null,
            'content' => $data['content'],
            'status' => $data['status'] ?? 'draft',
            'user_id' => $data['user_id'],
            'category_id' => $data['category_id'] ?? null,
            'featured_image' => $data['featured_image'] ?? null,
            'meta_title' => $data['meta_title'] ?? null,
            'meta_description' => $data['meta_description'] ?? null,
        ]);
    }

    public function update(Post $post, array $data): bool
    {
        return $post->update([
            'title' => $data['title'],
            'slug' => $data['slug'],
            'excerpt' => $data['excerpt'] ?? null,
            'content' => $data['content'],
            'status' => $data['status'],
            'category_id' => $data['category_id'] ?? null,
            'featured_image' => $data['featured_image'] ?? null,
            'meta_title' => $data['meta_title'] ?? null,
            'meta_description' => $data['meta_description'] ?? null,
        ]);
    }

    public function delete(Post $post): bool
    {
        return $post->delete();
    }

    public function publish(Post $post): bool
    {
        return $post->update(['status' => 'published', 'published_at' => now()]);
    }

    public function getPublished(): Collection
    {
        return $this->model
            ->with(['author', 'category'])
            ->published()
            ->latest('published_at')
            ->get();
    }
}
