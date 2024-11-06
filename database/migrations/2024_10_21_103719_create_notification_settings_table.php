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
        Schema::create('notification_settings', function (Blueprint $table) {
            $table->foreignUlid('user_id');
            $table->string('notifiable_type')->nullable();
            $table->foreignUlid('notifiable_id')->nullable();
            $table->enum('comment_mail', ['all', 'reply', 'leader', 'none'])->nullable();
            $table->boolean('invite_mail')->nullable();
            $table->boolean('change_mail')->nullable();
            $table->boolean('confirm_mail')->nullable();
            $table->boolean('cancel_mail')->nullable();
            $table->timestamps(6);

            $table->unique(['user_id', 'notifiable_type', 'notifiable_id'], 'unique_user_notifiable');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_settings');
    }
};
