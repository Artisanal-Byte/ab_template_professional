<?php

namespace App\Services\Tenancy;

use App\Models\Tenant;
use Illuminate\Support\Facades\DB;

class TenantConnectionManager
{
    public function configure(Tenant $tenant): void
    {
        config([
            'database.connections.tenant.host' => $tenant->db_host ?: config('database.connections.pgsql.host'),
            'database.connections.tenant.port' => $tenant->db_port ?: config('database.connections.pgsql.port'),
            'database.connections.tenant.database' => $tenant->db_name,
            'database.connections.tenant.username' => $tenant->db_username,
            'database.connections.tenant.password' => $tenant->databasePassword(),
        ]);

        DB::purge('tenant');
        DB::reconnect('tenant');
    }
}
