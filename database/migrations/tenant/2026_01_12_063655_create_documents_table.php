<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title', 200);
            $table->string('code', 50)->nullable();
            $table->string('status', 30)->default('draft'); // draft|active|archived
            $table->uuid('created_by');
            $table->timestampTz('effective_at')->nullable();
            $table->timestampTz('next_review_at')->nullable();
            $table->timestampsTz();

            $table->index('status');
            $table->index('code');
            $table->index('created_by');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
