<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'is_active' => true,
            'avatar_path' => null,
            'last_login_at' => null,
        ];
    }

    /**
     * Indicate that the user is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the user is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the user has a role.
     */
    public function withRole(?Role $role = null): static
    {
        return $this->state(fn (array $attributes) => [
            'role_id' => $role?->id ?? Role::factory()->create()->id,
        ]);
    }

    /**
     * Indicate that the user is an admin.
     */
    public function asAdmin(): static
    {
        return $this->withRole(Role::factory()->admin());
    }

    /**
     * Indicate that the user has logged in recently.
     */
    public function withLogin(): static
    {
        return $this->state(fn (array $attributes) => [
            'last_login_at' => now(),
        ]);
    }
}
