<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Role>
 */
class RoleFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->word(),
            'display_name' => fake()->words(2, true),
            'description' => fake()->sentence(),
        ];
    }

    /**
     * Indicate that the role is an admin role.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'admin',
            'display_name' => 'Administrator',
            'description' => 'System administrator with full access',
        ]);
    }

    /**
     * Indicate that the role is a user role.
     */
    public function user(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'user',
            'display_name' => 'User',
            'description' => 'Regular user with limited access',
        ]);
    }

    /**
     * Indicate that the role is an editor role.
     */
    public function editor(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'editor',
            'display_name' => 'Editor',
            'description' => 'Content editor with publishing rights',
        ]);
    }
}
