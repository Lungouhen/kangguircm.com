<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->sentence();
        
        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'content' => fake()->paragraphs(3, true),
            'excerpt' => fake()->paragraph(),
            'featured_image' => null,
            'status' => Post::STATUS_DRAFT,
            'published_at' => null,
            'author_id' => User::factory(),
            'views' => 0,
        ];
    }

    /**
     * Indicate that the post is published.
     */
    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Post::STATUS_PUBLISHED,
            'published_at' => now()->subDays(fake()->numberBetween(1, 30)),
        ]);
    }

    /**
     * Indicate that the post is a draft.
     */
    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Post::STATUS_DRAFT,
            'published_at' => null,
        ]);
    }

    /**
     * Indicate that the post is archived.
     */
    public function archived(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Post::STATUS_ARCHIVED,
            'published_at' => now()->subDays(fake()->numberBetween(30, 100)),
        ]);
    }

    /**
     * Indicate that the post has a specific author.
     */
    public function withAuthor(?User $author = null): static
    {
        return $this->state(fn (array $attributes) => [
            'author_id' => $author?->id ?? User::factory()->create()->id,
        ]);
    }

    /**
     * Indicate that the post has a featured image.
     */
    public function withFeaturedImage(): static
    {
        return $this->state(fn (array $attributes) => [
            'featured_image' => 'images/posts/' . fake()->uuid() . '.jpg',
        ]);
    }

    /**
     * Indicate that the post has many views.
     */
    public function withViews(int $views): static
    {
        return $this->state(fn (array $attributes) => [
            'views' => $views,
        ]);
    }
}
