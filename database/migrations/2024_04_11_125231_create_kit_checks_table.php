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
        Schema::create('kit_checks', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('user_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->foreignUlid('checked_by_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->date('checked_on');
            $table->text('comment')->nullable();
            $table->timestamps(6);

            $table->index(['user_id', 'checked_on'], 'user_id_checked_on');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kit_checks');
    }
};
