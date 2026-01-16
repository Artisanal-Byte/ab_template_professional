<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'pgsql') {
            return;
        }

        DB::statement('ALTER TABLE roles DROP CONSTRAINT IF EXISTS roles_tenant_id_name_guard_name_unique');
        DB::statement('ALTER TABLE model_has_roles DROP CONSTRAINT IF EXISTS model_has_roles_role_model_type_primary');
        DB::statement('ALTER TABLE model_has_roles DROP CONSTRAINT IF EXISTS model_has_roles_pkey');
        DB::statement('ALTER TABLE model_has_permissions DROP CONSTRAINT IF EXISTS model_has_permissions_permission_model_type_primary');
        DB::statement('ALTER TABLE model_has_permissions DROP CONSTRAINT IF EXISTS model_has_permissions_pkey');

        DB::statement('DROP INDEX IF EXISTS roles_team_foreign_key_index');
        DB::statement('DROP INDEX IF EXISTS model_has_roles_team_foreign_key_index');
        DB::statement('DROP INDEX IF EXISTS model_has_permissions_team_foreign_key_index');

        DB::statement('ALTER TABLE roles ALTER COLUMN tenant_id DROP NOT NULL');

        DB::statement('ALTER TABLE model_has_roles ALTER COLUMN tenant_id DROP NOT NULL');

        DB::statement('ALTER TABLE model_has_permissions ALTER COLUMN tenant_id DROP NOT NULL');

        DB::statement('UPDATE roles SET tenant_id = NULL');
        DB::statement('UPDATE model_has_roles SET tenant_id = NULL');
        DB::statement('UPDATE model_has_permissions SET tenant_id = NULL');

        DB::statement("DELETE FROM roles a USING roles b WHERE a.ctid < b.ctid AND COALESCE(a.tenant_id::text, '00000000-0000-0000-0000-000000000000') = COALESCE(b.tenant_id::text, '00000000-0000-0000-0000-000000000000') AND a.name = b.name AND a.guard_name = b.guard_name");
        DB::statement("DELETE FROM model_has_roles a USING model_has_roles b WHERE a.ctid < b.ctid AND COALESCE(a.tenant_id::text, '00000000-0000-0000-0000-000000000000') = COALESCE(b.tenant_id::text, '00000000-0000-0000-0000-000000000000') AND a.role_id = b.role_id AND a.model_id = b.model_id AND a.model_type = b.model_type");
        DB::statement("DELETE FROM model_has_permissions a USING model_has_permissions b WHERE a.ctid < b.ctid AND COALESCE(a.tenant_id::text, '00000000-0000-0000-0000-000000000000') = COALESCE(b.tenant_id::text, '00000000-0000-0000-0000-000000000000') AND a.permission_id = b.permission_id AND a.model_id = b.model_id AND a.model_type = b.model_type");

        DB::statement('ALTER TABLE roles ALTER COLUMN tenant_id TYPE uuid USING NULL');
        DB::statement('ALTER TABLE model_has_roles ALTER COLUMN tenant_id TYPE uuid USING NULL');
        DB::statement('ALTER TABLE model_has_permissions ALTER COLUMN tenant_id TYPE uuid USING NULL');

        DB::statement('CREATE INDEX roles_team_foreign_key_index ON roles (tenant_id)');
        DB::statement("CREATE UNIQUE INDEX roles_tenant_id_name_guard_name_unique ON roles (COALESCE(tenant_id, '00000000-0000-0000-0000-000000000000'), name, guard_name)");
        DB::statement('CREATE INDEX model_has_roles_team_foreign_key_index ON model_has_roles (tenant_id)');
        DB::statement('CREATE INDEX model_has_permissions_team_foreign_key_index ON model_has_permissions (tenant_id)');

        DB::statement("CREATE UNIQUE INDEX model_has_roles_role_model_type_primary ON model_has_roles (COALESCE(tenant_id, '00000000-0000-0000-0000-000000000000'), role_id, model_id, model_type)");
        DB::statement("CREATE UNIQUE INDEX model_has_permissions_permission_model_type_primary ON model_has_permissions (COALESCE(tenant_id, '00000000-0000-0000-0000-000000000000'), permission_id, model_id, model_type)");
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'pgsql') {
            return;
        }
    }
};
