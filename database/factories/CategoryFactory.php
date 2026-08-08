<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->word();
        
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => fake()->sentence(),
            'parent_id' => null,
        ];
    }

    /**
     * Indicate that the category has a parent.
     */
    public function withParent(?Category $parent = null): static
    {
        return $this->state(fn (array $attributes) => [
            'parent_id' => $parent?->id ?? Category::factory()->create()->id,
        ]);
    }

    /**
     * Indicate that the category is a child of another category.
     */
    public function child(): static
    {
        return $this->withParent();
    }
}
