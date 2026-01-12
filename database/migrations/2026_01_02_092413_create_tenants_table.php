<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            if (DB::getDriverName() === 'pgsql') {
                DB::statement('CREATE EXTENSION IF NOT EXISTS "pgcrypto";');
                $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            } else {
                $table->uuid('id')->primary();
            }

            $table->string('name', 200);
            $table->string('slug', 120)->unique();

            // Tenant DB routing
            $table->string('db_name', 80)->unique();
            $table->string('db_username', 80)->nullable();
            $table->text('db_password')->nullable();
            $table->string('db_host', 255)->nullable();
            $table->unsignedInteger('db_port')->nullable();

            $table->string('status', 20)->default('active'); // active|suspended
            $table->timestampTz('provisioned_at')->nullable();

            $table->timestampsTz();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};
