<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Create Roles
        $admin = Role::create([
            'name' => 'admin',
            'display_name' => 'Administrator',
            'description' => 'Full system access',
        ]);

        $cmsEditor = Role::create([
            'name' => 'cms_editor',
            'display_name' => 'CMS Editor',
            'description' => 'Can manage posts, pages, and media',
        ]);

        $emailManager = Role::create([
            'name' => 'email_manager',
            'display_name' => 'Email Marketing Manager',
            'description' => 'Can manage email campaigns and subscribers',
        ]);

        $hrManager = Role::create([
            'name' => 'hr_manager',
            'display_name' => 'HR Manager',
            'description' => 'Can manage employees, attendance, and payroll',
        ]);

        $employee = Role::create([
            'name' => 'employee',
            'display_name' => 'Employee',
            'description' => 'Basic employee access',
        ]);

        // Create Permissions
        $permissions = [
            // CMS Permissions
            ['name' => 'posts.view', 'display_name' => 'View Posts'],
            ['name' => 'posts.create', 'display_name' => 'Create Posts'],
            ['name' => 'posts.edit', 'display_name' => 'Edit Posts'],
            ['name' => 'posts.delete', 'display_name' => 'Delete Posts'],
            ['name' => 'posts.publish', 'display_name' => 'Publish Posts'],
            
            ['name' => 'pages.view', 'display_name' => 'View Pages'],
            ['name' => 'pages.create', 'display_name' => 'Create Pages'],
            ['name' => 'pages.edit', 'display_name' => 'Edit Pages'],
            ['name' => 'pages.delete', 'display_name' => 'Delete Pages'],
            
            ['name' => 'media.upload', 'display_name' => 'Upload Media'],
            ['name' => 'media.delete', 'display_name' => 'Delete Media'],
            ['name' => 'forms.manage', 'display_name' => 'Manage Forms and Submissions'],

            // Email Marketing Permissions
            ['name' => 'subscribers.view', 'display_name' => 'View Subscribers'],
            ['name' => 'subscribers.manage', 'display_name' => 'Manage Subscribers'],
            ['name' => 'lists.manage', 'display_name' => 'Manage Lists'],
            ['name' => 'templates.manage', 'display_name' => 'Manage Templates'],
            ['name' => 'campaigns.view', 'display_name' => 'View Campaigns'],
            ['name' => 'campaigns.create', 'display_name' => 'Create Campaigns'],
            ['name' => 'campaigns.send', 'display_name' => 'Send Campaigns'],

            // HRM Permissions
            ['name' => 'employees.view', 'display_name' => 'View Employees'],
            ['name' => 'employees.manage', 'display_name' => 'Manage Employees'],
            ['name' => 'attendance.view', 'display_name' => 'View Attendance'],
            ['name' => 'attendance.manage', 'display_name' => 'Manage Attendance'],
            ['name' => 'leave.approve', 'display_name' => 'Approve Leave Requests'],
            ['name' => 'payroll.view', 'display_name' => 'View Payroll'],
            ['name' => 'payroll.manage', 'display_name' => 'Manage Payroll'],

            // User Management
            ['name' => 'users.view', 'display_name' => 'View Users'],
            ['name' => 'users.manage', 'display_name' => 'Manage Users'],
            ['name' => 'roles.manage', 'display_name' => 'Manage Roles'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }

        // Assign permissions to roles
        $admin->syncPermissions(Permission::pluck('name')->toArray());

        $cmsEditor->syncPermissions([
            'posts.view', 'posts.create', 'posts.edit', 'posts.delete', 'posts.publish',
            'pages.view', 'pages.create', 'pages.edit', 'pages.delete',
            'media.upload', 'media.delete', 'forms.manage',
        ]);

        $emailManager->syncPermissions([
            'subscribers.view', 'subscribers.manage', 'lists.manage',
            'templates.manage', 'campaigns.view', 'campaigns.create', 'campaigns.send',
        ]);

        $hrManager->syncPermissions([
            'employees.view', 'employees.manage', 'attendance.view', 'attendance.manage',
            'leave.approve', 'payroll.view', 'payroll.manage',
        ]);

        $employee->syncPermissions([
            'employees.view', 'attendance.view', 'leave.approve',
        ]);
    }
}
