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
        Schema::create('change_comments', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('change_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->text('body');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('change_comments');
    }
};
