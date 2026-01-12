<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use App\Services\Tenancy\TenantConnectionManager;
use App\Services\Tenancy\TenantProvisioner;
use App\Support\CurrentTenant;
use Closure;
use Illuminate\Http\Request;
use Spatie\Permission\PermissionRegistrar;
use Symfony\Component\HttpFoundation\Response;

class SetTenantContext
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $tenantId = $request->header('X-Tenant-ID') ?: $request->session()->get('tenant_id');

        if ($tenantId) {
            $tenant = Tenant::query()->where('id', $tenantId)->first();

            if ($tenant) {
                if (! $tenant->provisioned_at) {
                    app(TenantProvisioner::class)->ensureProvisioned($tenant);
                    $tenant->refresh();
                }

                app(CurrentTenant::class)->set($tenant);
                app(PermissionRegistrar::class)->setPermissionsTeamId($tenant->id);
                app(TenantConnectionManager::class)->configure($tenant);
            }
        } else {
            app(CurrentTenant::class)->set(null);
            app(PermissionRegistrar::class)->setPermissionsTeamId(null);
        }

        return $next($request);
    }
}
