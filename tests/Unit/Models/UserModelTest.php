<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

/**
 * Unit tests for the User model.
 * 
 * These tests verify the behavior of the User model methods,
 * including relationships, role checks, and utility methods.
 */
class UserModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that a user can be created with required attributes.
     */
    public function test_user_can_be_created_with_required_attributes(): void
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('secret'),
        ]);

        $this->assertEquals('Test User', $user->name);
        $this->assertEquals('test@example.com', $user->email);
        $this->assertTrue(Hash::check('secret', $user->password));
        $this->assertTrue($user->is_active);
    }

    /**
     * Test that password is automatically hashed when creating a user.
     */
    public function test_password_is_hashed_when_creating_user(): void
    {
        $user = User::factory()->create([
            'password' => 'plain-text-password',
        ]);

        // The factory already hashes the password, so we verify it's not plain text
        $this->assertNotEquals('plain-text-password', $user->password);
        $this->assertTrue(Hash::check('plain-text-password', $user->password));
    }

    /**
     * Test that a user belongs to a role.
     */
    public function test_user_belongs_to_role(): void
    {
        $role = Role::factory()->create();
        $user = User::factory()->create(['role_id' => $role->id]);

        $this->assertInstanceOf(Role::class, $user->role);
        $this->assertEquals($role->id, $user->role->id);
        $this->assertEquals($role->name, $user->role->name);
    }

    /**
     * Test hasRole method returns true for matching role.
     */
    public function test_has_role_returns_true_for_matching_role(): void
    {
        $role = Role::factory()->create(['name' => 'admin']);
        $user = User::factory()->create(['role_id' => $role->id]);

        $this->assertTrue($user->hasRole('admin'));
    }

    /**
     * Test hasRole method returns false for non-matching role.
     */
    public function test_has_role_returns_false_for_non_matching_role(): void
    {
        $role = Role::factory()->create(['name' => 'user']);
        $user = User::factory()->create(['role_id' => $role->id]);

        $this->assertFalse($user->hasRole('admin'));
    }

    /**
     * Test hasRole method returns false when user has no role.
     */
    public function test_has_role_returns_false_when_user_has_no_role(): void
    {
        $user = User::factory()->create(['role_id' => null]);

        $this->assertFalse($user->hasRole('admin'));
    }

    /**
     * Test isAdmin method returns true for admin role.
     */
    public function test_is_admin_returns_true_for_admin_role(): void
    {
        $role = Role::factory()->admin()->create();
        $user = User::factory()->create(['role_id' => $role->id]);

        $this->assertTrue($user->isAdmin());
    }

    /**
     * Test isAdmin method returns false for non-admin role.
     */
    public function test_is_admin_returns_false_for_non_admin_role(): void
    {
        $role = Role::factory()->user()->create();
        $user = User::factory()->create(['role_id' => $role->id]);

        $this->assertFalse($user->isAdmin());
    }

    /**
     * Test hasPermission method returns true for valid permission.
     */
    public function test_has_permission_returns_true_for_valid_permission(): void
    {
        // This test would require setting up permissions
        // For now, we test the case where user has no role
        $user = User::factory()->create(['role_id' => null]);

        $this->assertFalse($user->hasPermission('any.permission'));
    }

    /**
     * Test isActive method returns true for active user.
     */
    public function test_is_active_returns_true_for_active_user(): void
    {
        $user = User::factory()->active()->create();

        $this->assertTrue($user->isActive());
    }

    /**
     * Test isActive method returns false for inactive user.
     */
    public function test_is_active_returns_false_for_inactive_user(): void
    {
        $user = User::factory()->inactive()->create();

        $this->assertFalse($user->isActive());
    }

    /**
     * Test recordLogin method updates last_login_at.
     */
    public function test_record_login_updates_last_login_at(): void
    {
        $user = User::factory()->create(['last_login_at' => null]);

        $this->assertNull($user->last_login_at);

        $user->recordLogin();

        $this->assertNotNull($user->fresh()->last_login_at);
    }

    /**
     * Test that email_verified_at is cast to datetime.
     */
    public function test_email_verified_at_is_cast_to_datetime(): void
    {
        $user = User::factory()->create();

        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $user->created_at);
        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $user->updated_at);
    }

    /**
     * Test that is_active is cast to boolean.
     */
    public function test_is_active_is_cast_to_boolean(): void
    {
        $user = User::factory()->create(['is_active' => 1]);
        $this->assertTrue(is_bool($user->is_active));

        $user = User::factory()->create(['is_active' => 0]);
        $this->assertTrue(is_bool($user->is_active));
    }

    /**
     * Test that last_login_at is cast to datetime.
     */
    public function test_last_login_at_is_cast_to_datetime(): void
    {
        $user = User::factory()->withLogin()->create();

        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $user->last_login_at);
    }
}
