<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workflow_instances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('document_version_id');
            $table->string('status', 30)->default('in_progress'); // in_progress|approved|rejected
            $table->uuid('started_by');
            $table->timestampTz('completed_at')->nullable();
            $table->timestampsTz();

            $table->foreign('document_version_id')
                ->references('id')
                ->on('document_versions')
                ->onDelete('cascade');

            $table->index(['document_version_id', 'status']);
            $table->index('started_by');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workflow_instances');
    }
};
