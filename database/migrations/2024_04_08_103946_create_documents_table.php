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
        Schema::create('documents', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('title', 100);
            $table->string('category', 100)->index();
            $table->text('description')->nullable();
            $table->string('file_name');
            $table->string('file_path');
            $table->integer('file_size');
            $table->string('file_type');
            $table->softDeletes(precision: 6);
            $table->timestamps(6);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
