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
        Schema::create('bookables', function (Blueprint $table) {
            $table->foreignUlid('booking_id');
            $table->ulidMorphs('bookable'); // bookable_id, bookable_type

            $table->unique(['booking_id', 'bookable_id', 'bookable_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookables');
    }
};
