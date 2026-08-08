<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->first();
        $cmsEditorRole = Role::where('name', 'cms_editor')->first();
        $emailManagerRole = Role::where('name', 'email_manager')->first();
        $hrManagerRole = Role::where('name', 'hr_manager')->first();
        $employeeRole = Role::where('name', 'employee')->first();

        // Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@kanggui-rcm.com',
            'password' => Hash::make('password123'),
            'role_id' => $adminRole?->id,
            'is_active' => true,
        ]);

        // CMS Editor
        User::create([
            'name' => 'CMS Editor',
            'email' => 'editor@kanggui-rcm.com',
            'password' => Hash::make('password123'),
            'role_id' => $cmsEditorRole?->id,
            'is_active' => true,
        ]);

        // Email Manager
        User::create([
            'name' => 'Email Manager',
            'email' => 'email@kanggui-rcm.com',
            'password' => Hash::make('password123'),
            'role_id' => $emailManagerRole?->id,
            'is_active' => true,
        ]);

        // HR Manager
        User::create([
            'name' => 'HR Manager',
            'email' => 'hr@kanggui-rcm.com',
            'password' => Hash::make('password123'),
            'role_id' => $hrManagerRole?->id,
            'is_active' => true,
        ]);

        // Employee
        User::create([
            'name' => 'John Employee',
            'email' => 'employee@kanggui-rcm.com',
            'password' => Hash::make('password123'),
            'role_id' => $employeeRole?->id,
            'is_active' => true,
        ]);
    }
}
