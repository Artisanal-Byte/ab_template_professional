<?php

namespace App\Providers;

use App\Models\User;
use App\Support\CurrentTenant;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Gate::define('manage-admins', fn (User $user) => $user->hasRole('web_admin'));
        Gate::define('manage-tenants', fn (User $user) => $user->hasRole('admin'));

        Gate::define('view-tenant-dashboard', fn (User $user): bool => Gate::allows('manage-tenant-staff'));

        Gate::define('manage-tenant-staff', function (User $user): bool {
            $tenant = app(CurrentTenant::class)->get();

            if (! $tenant) {
                return false;
            }

            $membership = $user->tenantMemberships()
                ->where('tenant_id', $tenant->id)
                ->where('status', 'active')
                ->first();

            return $membership?->membership_role === 'owner';
        });

        Gate::define('manage-tenant-roles', fn (User $user): bool => Gate::allows('manage-tenant-staff'));
    }
}
