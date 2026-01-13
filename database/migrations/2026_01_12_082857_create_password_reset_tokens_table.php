<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // If table doesn't exist, create it
        if (!Schema::hasTable('password_reset_tokens')) {
            Schema::create('password_reset_tokens', function (Blueprint $table) {
                $table->string('email')->primary();
                $table->string('token');
                $table->timestampTz('created_at')->nullable();
            });

            return;
        }

        // If table exists, ensure required columns exist
        Schema::table('password_reset_tokens', function (Blueprint $table) {
            if (!Schema::hasColumn('password_reset_tokens', 'email')) {
                $table->string('email');
            }

            if (!Schema::hasColumn('password_reset_tokens', 'token')) {
                $table->string('token');
            }

            if (!Schema::hasColumn('password_reset_tokens', 'created_at')) {
                $table->timestampTz('created_at')->nullable();
            }
        });

        // Ensure primary key on email (best-effort; some DBs may already have PK differently)
        // If your DB might already have a different PK, skip this and handle manually.
        // You can add a guarded attempt like below for MySQL/PG, but it may fail if PK already exists.
        // So leaving it out to keep migration safe.
    }

    public function down(): void
    {
        Schema::dropIfExists('password_reset_tokens');
    }
};
