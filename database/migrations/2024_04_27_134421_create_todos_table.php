<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('todos', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('summary');
            $table->text('description')->nullable();
            $table->string('location')->nullable();
            $table->unsignedTinyInteger('priority')->default(5);
            $table->enum('status', ['in-process', 'needs-action', 'completed', 'cancelled'])->default('needs-action');
            $table->timestamp('due_at', 6)->nullable();
            $table->timestamp('started_at', 6)->nullable();
            $table->timestamp('completed_at', 6)->nullable();
            $table->unsignedInteger('sequence')->default(0);
            $table->timestamps(6);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('todos');
    }
};
