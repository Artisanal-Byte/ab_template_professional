<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_assignments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('document_version_id');
            $table->uuid('core_user_id');
            $table->string('status', 30)->default('assigned'); // assigned|completed|overdue
            $table->timestampTz('due_at')->nullable();
            $table->timestampTz('completed_at')->nullable();
            $table->timestampsTz();

            $table->foreign('document_version_id')
                ->references('id')
                ->on('document_versions')
                ->onDelete('cascade');

            $table->index(['document_version_id', 'status']);
            $table->index('core_user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_assignments');
    }
};
