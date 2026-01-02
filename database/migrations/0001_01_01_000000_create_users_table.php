<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('name', 150);
            $table->string('email', 190)->unique();
            $table->string('password'); // hashed password
            $table->string('status', 20)->default('active'); // active|locked|disabled

            $table->timestampTz('last_login_at')->nullable();
            $table->timestampsTz();

            $table->index('status');
            $table->index('last_login_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
