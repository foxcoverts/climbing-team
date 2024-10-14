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
        Schema::create('todo_user', function (Blueprint $table) {
            $table->foreignUlid('todo_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('user_id')->constrained()->cascadeOnDelete();
            $table->enum('status', ['accepted', 'in-process', 'tentative', 'needs-action', 'completed', 'declined'])
                ->default('needs-action');
            $table->unsignedTinyInteger('percent')->default(0);
            $table->text('comment')->nullable();
            $table->string('token', 64)->nullable()->unique();
            $table->timestamps(6);

            $table->unique(['todo_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('todo_user');
    }
};
