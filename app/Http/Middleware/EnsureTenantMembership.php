<?php

namespace App\Http\Middleware;

use App\Models\TenantUser;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTenantMembership
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $tenantId = $request->header('X-Tenant-ID') ?: $request->session()->get('tenant_id');

        if (! $tenantId) {
            abort(403);
        }

        $membership = TenantUser::query()
            ->where('tenant_id', $tenantId)
            ->where('user_id', $request->user()?->id)
            ->where('status', 'active')
            ->first();

        if (! $membership) {
            abort(403);
        }

        $request->attributes->set('tenant_membership', $membership);

        return $next($request);
    }
}
