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
        Schema::create('change_attendees', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('change_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignUlid('attendee_id')
                ->constrained(table: 'users')
                ->cascadeOnDelete();
            $table->enum(
                'attendee_status',
                ['accepted', 'tentative', 'declined', 'needs-update']
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('change_attendees');
    }
};
