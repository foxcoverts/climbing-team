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
        Schema::create('scout_permits', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('activity');
            $table->string('category');
            $table->string('permit_type');
            $table->text('restrictions')->nullable();
            $table->timestamps(6);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scout_permits');
    }
};
