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
        Schema::create('booking_settings', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('user_id');
            $table->foreignUlid('booking_id')->nullable();
            $table->enum('comment_mail', ['all', 'leader', 'none'])->nullable();
            $table->boolean('invite_mail')->nullable();
            $table->boolean('change_mail')->nullable();
            $table->boolean('confirm_mail')->nullable();
            $table->boolean('cancel_mail')->nullable();
            $table->timestamps(6);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_settings');
    }
};
