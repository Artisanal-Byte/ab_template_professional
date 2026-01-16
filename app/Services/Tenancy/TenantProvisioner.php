<?php

namespace App\Services\Tenancy;

use App\Models\Tenant;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TenantProvisioner
{
    public function __construct(private TenantConnectionManager $connectionManager) {}

    public function ensureProvisioned(Tenant $tenant): void
    {
        if ($tenant->provisioned_at) {
            return;
        }

        $dbName = $tenant->db_name;
        $dbUser = $tenant->db_username ?: $this->defaultUsername($tenant);
        $dbPassword = $tenant->db_password ? $tenant->databasePassword() : Str::random(32);

        $dbHost = $tenant->db_host ?: config('database.connections.pgsql.host');
        $dbPort = $tenant->db_port ?: config('database.connections.pgsql.port');

        $connection = DB::connection('pgsql');
        $safeDbName = $this->quoteIdentifier($dbName);
        $safeDbUser = $this->quoteIdentifier($dbUser);

        try {
            if (! $this->roleExists($dbUser)) {
                $safePassword = $this->quoteStringLiteral($dbPassword);
                $connection->statement("CREATE USER {$safeDbUser} WITH PASSWORD {$safePassword}");
            }

            if (! $this->databaseExists($dbName)) {
                $connection->statement("CREATE DATABASE {$safeDbName}");
            }

            $connection->statement("GRANT ALL PRIVILEGES ON DATABASE {$safeDbName} TO {$safeDbUser}");
            $connection->statement("ALTER DATABASE {$safeDbName} OWNER TO {$safeDbUser}");

            $tenant->forceFill([
                'db_username' => $dbUser,
                'db_password' => Crypt::encryptString($dbPassword),
                'db_host' => $dbHost,
                'db_port' => $dbPort,
                'provisioned_at' => now(),
            ])->save();

            $this->connectionManager->configure($tenant);

            Artisan::call('migrate', [
                '--database' => 'tenant',
                '--path' => 'database/migrations/tenant',
                '--force' => true,
                '--no-interaction' => true,
            ]);
        } catch (\Throwable $exception) {
            Log::error('Tenant provisioning failed', [
                'tenant_id' => $tenant->id,
                'db_name' => $dbName,
                'error' => $exception->getMessage(),
            ]);

            throw $exception;
        }
    }

    private function databaseExists(string $dbName): bool
    {
        return DB::connection('pgsql')
            ->table('pg_database')
            ->where('datname', $dbName)
            ->exists();
    }

    private function roleExists(string $roleName): bool
    {
        return DB::connection('pgsql')
            ->table('pg_roles')
            ->where('rolname', $roleName)
            ->exists();
    }

    private function defaultUsername(Tenant $tenant): string
    {
        return 'tenant_'.$tenant->slug;
    }

    private function quoteIdentifier(string $identifier): string
    {
        if (! preg_match('/^[a-z0-9_]+$/', $identifier)) {
            throw new \InvalidArgumentException('Invalid SQL identifier.');
        }

        return '"'.$identifier.'"';
    }

    private function quoteStringLiteral(string $value): string
    {
        return DB::connection('pgsql')->getPdo()->quote($value);
    }
}
