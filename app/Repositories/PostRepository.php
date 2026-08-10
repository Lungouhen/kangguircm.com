<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class PostRepository
{
    public function __construct(private readonly Post $model) {}

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->newQuery()->with(['author', 'categories'])->latest()->paginate($perPage);
    }

    public function find(int $id): ?Post
    {
        return $this->model->newQuery()->with(['author', 'categories'])->find($id);
    }

    public function create(array $data): Post
    {
        $post = $this->model->newQuery()->create($this->attributes($data));
        $post->categories()->sync(array_filter([$data['category_id'] ?? null]));

        return $post;
    }

    public function update(Post $post, array $data): bool
    {
        $updated = $post->update($this->attributes($data, $post));
        $post->categories()->sync(array_filter([$data['category_id'] ?? null]));

        return $updated;
    }

    public function delete(Post $post): bool
    {
        return (bool) $post->delete();
    }

    public function publish(Post $post): bool
    {
        return $post->update(['status' => Post::STATUS_PUBLISHED, 'published_at' => now()]);
    }

    public function getPublished(): Collection
    {
        return $this->model->newQuery()->with(['author', 'categories'])->published()->latest('published_at')->get();
    }

    private function attributes(array $data, ?Post $post = null): array
    {
        return [
            'title' => $data['title'],
            'slug' => $data['slug'],
            'excerpt' => $data['excerpt'] ?? null,
            'content' => $data['content'],
            'status' => $data['status'] ?? Post::STATUS_DRAFT,
            'author_id' => $data['author_id'] ?? $post?->author_id,
            'featured_image' => $data['featured_image'] ?? $post?->featured_image,
            'meta_title' => $data['meta_title'] ?? null,
            'meta_description' => $data['meta_description'] ?? null,
            'canonical_url' => $data['canonical_url'] ?? null,
            'noindex' => (bool) ($data['noindex'] ?? false),
            'published_at' => ($data['status'] ?? null) === Post::STATUS_PUBLISHED
                ? ($post?->published_at ?? now())
                : null,
        ];
    }
}
