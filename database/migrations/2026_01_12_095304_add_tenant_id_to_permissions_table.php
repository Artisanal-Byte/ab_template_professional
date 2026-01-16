<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            if (! Schema::hasColumn('permissions', 'tenant_id')) {
                $table->uuid('tenant_id')->nullable()->after('id');
            }
        });

        if (DB::getDriverName() !== 'pgsql') {
            return;
        }

        DB::statement('ALTER TABLE permissions DROP CONSTRAINT IF EXISTS permissions_name_guard_name_unique');
        DB::statement('DROP INDEX IF EXISTS permissions_tenant_id_name_guard_name_unique');
        DB::statement('DROP INDEX IF EXISTS permissions_tenant_id_index');

        DB::statement('CREATE INDEX permissions_tenant_id_index ON permissions (tenant_id)');
        DB::statement("CREATE UNIQUE INDEX permissions_tenant_id_name_guard_name_unique ON permissions (COALESCE(tenant_id, '00000000-0000-0000-0000-000000000000'), name, guard_name)");
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('DROP INDEX IF EXISTS permissions_tenant_id_name_guard_name_unique');
            DB::statement('DROP INDEX IF EXISTS permissions_tenant_id_index');
        }

        Schema::table('permissions', function (Blueprint $table) {
            if (Schema::hasColumn('permissions', 'tenant_id')) {
                $table->dropColumn('tenant_id');
            }
        });

        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE permissions ADD CONSTRAINT permissions_name_guard_name_unique UNIQUE (name, guard_name)');
        }
    }
};
