<?php

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

uses(RefreshDatabase::class);

beforeEach(function () {
    app(PermissionRegistrar::class)->forgetCachedPermissions();
    app(PermissionRegistrar::class)->setPermissionsTeamId(null);

    Role::create(['name' => 'admin', 'guard_name' => 'web', 'tenant_id' => null]);
    Role::create(['name' => 'organization_owner', 'guard_name' => 'web', 'tenant_id' => null]);
});

it('updates tenant details', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $owner = User::factory()->create([
        'email' => 'owner@old.com',
        'password' => Hash::make('old-password'),
    ]);

    $tenant = Tenant::factory()->create([
        'name' => 'Old Name',
        'slug' => 'old_slug',
    ]);

    $tenant->memberships()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $owner->id,
        'membership_role' => 'owner',
        'status' => 'active',
    ]);

    $this->actingAs($admin);

    $response = $this->patch(route('admin.tenants.update', $tenant), [
        'name' => 'Updated Name',
        'slug' => 'updated_slug',
        'owner_email' => 'owner@new.com',
        'owner_password' => 'new-password',
    ]);

    $response->assertRedirect(route('admin.tenants.index'));

    $tenant->refresh();
    expect($tenant->name)->toBe('Updated Name')
        ->and($tenant->slug)->toBe('updated_slug');

    $owner->refresh();
    expect($owner->email)->toBe('owner@new.com')
        ->and(Hash::check('new-password', $owner->password))->toBeTrue();

    app(PermissionRegistrar::class)->setPermissionsTeamId($tenant->id);
    expect($owner->roles()->where('roles.tenant_id', $tenant->id)->where('name', 'Administrator')->exists())
        ->toBeTrue();
});

it('keeps other tenant owners intact when switching to a new owner email', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $sharedOwner = User::factory()->create([
        'email' => 'shared@old.com',
    ]);

    $tenantA = Tenant::factory()->create([
        'name' => 'Tenant A',
        'slug' => 'tenant_a',
    ]);

    $tenantB = Tenant::factory()->create([
        'name' => 'Tenant B',
        'slug' => 'tenant_b',
    ]);

    $tenantA->memberships()->create([
        'tenant_id' => $tenantA->id,
        'user_id' => $sharedOwner->id,
        'membership_role' => 'owner',
        'status' => 'active',
    ]);

    $tenantB->memberships()->create([
        'tenant_id' => $tenantB->id,
        'user_id' => $sharedOwner->id,
        'membership_role' => 'owner',
        'status' => 'active',
    ]);

    $this->actingAs($admin);

    $response = $this->patch(route('admin.tenants.update', $tenantA), [
        'name' => 'Tenant A',
        'slug' => 'tenant_a',
        'owner_email' => 'new-owner@acme.test',
        'owner_password' => 'new-password',
    ]);

    $response->assertRedirect(route('admin.tenants.index'));

    $tenantAOwner = $tenantA->memberships()
        ->where('membership_role', 'owner')
        ->with('user')
        ->first();

    $tenantBOwner = $tenantB->memberships()
        ->where('membership_role', 'owner')
        ->with('user')
        ->first();

    $sharedOwner->refresh();

    expect($tenantAOwner?->user?->email)->toBe('new-owner@acme.test')
        ->and($tenantBOwner?->user?->email)->toBe('shared@old.com')
        ->and($sharedOwner->email)->toBe('shared@old.com');

    $newOwner = User::query()->where('email', 'new-owner@acme.test')->first();

    expect($newOwner)->not->toBeNull()
        ->and($newOwner?->hasRole('organization_owner'))->toBeTrue()
        ->and(Hash::check('new-password', $newOwner?->password ?? ''))->toBeTrue();
});

it('assigns an owner when missing using the provided email and password', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $tenant = Tenant::factory()->create([
        'name' => 'Acme Org',
        'slug' => 'acme_org',
    ]);

    $this->actingAs($admin);

    $response = $this->patch(route('admin.tenants.update', $tenant), [
        'name' => 'Acme Org',
        'slug' => 'acme_org',
        'owner_email' => 'owner@acme.test',
        'owner_password' => 'new-password',
    ]);

    $response->assertRedirect(route('admin.tenants.index'));

    $owner = User::query()->where('email', 'owner@acme.test')->first();
    $tenant->refresh();

    expect($owner)->not->toBeNull()
        ->and($owner?->hasRole('organization_owner'))->toBeTrue()
        ->and(Hash::check('new-password', $owner?->password ?? ''))->toBeTrue();

    $membership = $tenant->memberships()
        ->where('membership_role', 'owner')
        ->where('user_id', $owner?->id)
        ->first();

    expect($membership)->not->toBeNull();

    app(PermissionRegistrar::class)->setPermissionsTeamId($tenant->id);
    expect($owner?->roles()->where('roles.tenant_id', $tenant->id)->where('name', 'Administrator')->exists())
        ->toBeTrue();
});
