<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

uses(RefreshDatabase::class);

beforeEach(function () {
    app(PermissionRegistrar::class)->forgetCachedPermissions();
    app(PermissionRegistrar::class)->setPermissionsTeamId(null);

    Role::create(['name' => 'admin', 'guard_name' => 'web', 'tenant_id' => null]);
    Role::create(['name' => 'web_admin', 'guard_name' => 'web', 'tenant_id' => null]);
});

it('updates admin status with valid values', function () {
    $webAdmin = User::factory()->create();
    $webAdmin->assignRole('web_admin');

    $admin = User::factory()->create([
        'status' => 'active',
    ]);
    $admin->assignRole('admin');

    $response = $this
        ->actingAs($webAdmin)
        ->patch(route('platform.admin-users.update', $admin), [
            'name' => $admin->name,
            'email' => $admin->email,
            'status' => 'locked',
        ]);

    $response->assertRedirect(route('platform.admin-users.index'));
    expect($admin->refresh()->status)->toBe('locked');
});

it('rejects invalid admin status updates', function () {
    $webAdmin = User::factory()->create();
    $webAdmin->assignRole('web_admin');

    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $response = $this
        ->actingAs($webAdmin)
        ->from(route('platform.admin-users.index'))
        ->patch(route('platform.admin-users.update', $admin), [
            'name' => $admin->name,
            'email' => $admin->email,
            'status' => 'pending',
        ]);

    $response->assertSessionHasErrors(['status']);
});
