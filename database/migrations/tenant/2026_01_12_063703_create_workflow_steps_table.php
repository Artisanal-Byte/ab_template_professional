<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workflow_steps', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('workflow_instance_id');
            $table->string('step_name', 120);
            $table->uuid('assigned_to')->nullable();
            $table->string('status', 30)->default('pending'); // pending|approved|rejected
            $table->timestampTz('completed_at')->nullable();
            $table->timestampsTz();

            $table->foreign('workflow_instance_id')
                ->references('id')
                ->on('workflow_instances')
                ->onDelete('cascade');

            $table->index(['workflow_instance_id', 'status']);
            $table->index('assigned_to');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workflow_steps');
    }
};
