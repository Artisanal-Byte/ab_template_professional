<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tenant_users', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->uuid('tenant_id');

            // Tenant contact info (NOT app login users)
            $table->string('name', 150);
            $table->string('email', 190)->nullable();
            $table->string('role_label', 80)->nullable(); // e.g., QA Manager, Approver
            $table->string('status', 20)->default('active'); // active|disabled

            $table->timestampsTz();

            $table->foreign('tenant_id')
                ->references('id')
                ->on('tenants')
                ->onDelete('cascade');

            $table->index('tenant_id');
            $table->index('status');
        });

        /**
         * Ensure (tenant_id, email) is unique ONLY when email is not null.
         * PostgreSQL allows multiple NULLs in UNIQUE, but this partial index is cleaner and explicit.
         */
        DB::statement(
            'CREATE UNIQUE INDEX tenant_users_tenant_id_email_unique_not_null
             ON tenant_users (tenant_id, email)
             WHERE email IS NOT NULL;'
        );
    }

    public function down(): void
    {
        // Index will drop automatically when table drops, but this is safe:
        DB::statement('DROP INDEX IF EXISTS tenant_users_tenant_id_email_unique_not_null;');

        Schema::dropIfExists('tenant_users');
    }
};
