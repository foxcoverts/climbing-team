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
        // VTODO
        Schema::create('todos', function (Blueprint $table) {
            $table->ulid('id')->primary(); // VTODO: UID
            $table->string('summary'); // VTODO: SUMMARY
            $table->text('description'); // VTODO: DESCRIPTION
            $table->string('location')->nullable(); // VTODO: LOCATION
            $table->unsignedTinyInteger('priority')->default(5); // VTODO: PRIORITY (HIGH = 1, MEDIUM = 5, LOW = 9)
            $table->enum('status', ['needs-action', 'in-process', 'completed', 'cancelled'])->default('needs-action'); // VTODO: STATUS
            $table->timestamp('due_at', 6)->nullable(); // VTODO: DUE
            $table->timestamp('started_at', 6)->nullable(); // VTODO: DTSTART
            $table->timestamp('completed_at', 6)->nullable(); // VTODO: COMPLETED
            $table->unsignedInteger('sequence')->default(0); // VTODO: SEQUENCE
            $table->timestamps(6); // VTODO: CREATED, LAST-MODIFIED

            // VTODO:ORGANIZER
        });

        // VTODO:ATTENDEE
        Schema::create('todo_user', function (Blueprint $table) {
            $table->foreignUlid('todo_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('user_id')->constrained()->cascadeOnDelete();
            $table->enum('status', ['accepted', 'in-process', 'tentative', 'needs-action', 'completed', 'declined'])
                ->default('needs-action'); // VTODO: STATUS
            $table->unsignedTinyInteger('percent')->default(0); // VTODO: PERCENT-COMPLETE
            $table->timestamps(6);

            $table->unique(['todo_id', 'user_id']);
        });

        // VTODO:RELATED-TO
        // VEVENT:RELATED-TO
        Schema::create('booking_todo', function (Blueprint $table) {
            $table->foreignUlid('booking_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('todo_id')->constrained()->cascadeOnDelete();
            $table->timestamps(6);

            $table->unique(['booking_id', 'todo_id']);
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
