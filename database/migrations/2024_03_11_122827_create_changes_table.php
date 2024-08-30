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
        Schema::create('changes', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->timestamps(6);
            $table->foreignUlid('booking_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignUlid('author_id')
                ->constrained(table: 'users')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('changes');
    }
};
