<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_versions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('document_id');
            $table->string('version', 20)->default('1.0');
            $table->longText('content')->nullable();
            $table->string('status', 30)->default('draft'); // draft|published|archived
            $table->uuid('created_by');
            $table->timestampTz('published_at')->nullable();
            $table->timestampsTz();

            $table->foreign('document_id')
                ->references('id')
                ->on('documents')
                ->onDelete('cascade');

            $table->index(['document_id', 'status']);
            $table->index('created_by');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_versions');
    }
};
