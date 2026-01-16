<?php

use App\Models\Tenant;
use App\Models\TenantUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

uses(RefreshDatabase::class);

beforeEach(function () {
    app(PermissionRegistrar::class)->forgetCachedPermissions();
    app(PermissionRegistrar::class)->setPermissionsTeamId(null);

    Role::create(['name' => 'admin', 'guard_name' => 'web', 'tenant_id' => null]);
});

it('includes tenant owner details in the tenants listing', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $owner = User::factory()->create([
        'name' => 'Owner Name',
        'email' => 'owner@example.com',
    ]);

    $tenant = Tenant::factory()->create([
        'name' => 'Acme Org',
        'slug' => 'acme_org',
    ]);

    TenantUser::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $owner->id,
        'membership_role' => 'owner',
        'status' => 'active',
    ]);

    $this->actingAs($admin)
        ->get(route('admin.tenants.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('admin/Tenants')
            ->where('tenants.0.name', 'Acme Org')
            ->where('tenants.0.slug', 'acme_org')
            ->where('tenants.0.owner.email', 'owner@example.com')
        );
});
