<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (DB::getDriverName() === 'pgsql') {
                DB::statement('CREATE EXTENSION IF NOT EXISTS "pgcrypto";');
                $table->uuid('uuid')->default(DB::raw('gen_random_uuid()'))->unique()->after('id');
            } else {
                $table->uuid('uuid')->unique()->after('id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
};
