<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Post;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class PostRepository
{
    public function __construct(
        private Post $post
    ) {}

    public function paginate(int $perPage = 15, ?string $search = null, ?string $status = null): LengthAwarePaginator
    {
        $query = $this->post->with(['author', 'category']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        return $query->latest()->paginate($perPage);
    }

    public function find(int $id): ?Post
    {
        return $this->post->with(['author', 'category'])->find($id);
    }

    public function create(array $data): Post
    {
        return $this->post->create($data);
    }

    public function update(Post $post, array $data): bool
    {
        return $post->update($data);
    }

    public function delete(Post $post): bool
    {
        return $post->delete();
    }

    public function publish(Post $post): bool
    {
        return $post->update([
            'status' => 'published',
            'published_at' => now(),
        ]);
    }

    public function draft(Post $post): bool
    {
        return $post->update([
            'status' => 'draft',
            'published_at' => null,
        ]);
    }

    public function getPublishedPosts(int $limit = 6): Collection
    {
        return $this->post->published()
            ->with(['author', 'category'])
            ->latest('published_at')
            ->limit($limit)
            ->get();
    }

    public function findBySlug(string $slug): ?Post
    {
        return $this->post->with(['author', 'category'])
            ->where('slug', $slug)
            ->first();
    }
}
