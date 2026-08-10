<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('SEED_ADMIN_EMAIL');
        $password = env('SEED_ADMIN_PASSWORD');
        if (!$email && !$password) {
            $this->command?->warn('Admin user not seeded. Set SEED_ADMIN_EMAIL and SEED_ADMIN_PASSWORD explicitly.');
            return;
        }
        if (!$email || !$password || strlen($password) < 12) {
            throw new RuntimeException('Seed admin credentials must include a valid email and password of at least 12 characters.');
        }

        $role = Role::query()->where('name', 'admin')->firstOrFail();
        User::query()->updateOrCreate(['email' => $email], [
            'name' => env('SEED_ADMIN_NAME', 'Administrator'),
            'password' => Hash::make($password),
            'role_id' => $role->id,
            'is_active' => true,
        ]);
    }
}
