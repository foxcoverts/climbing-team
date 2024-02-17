<?php

use App\Models\Booking;
use App\Models\User;
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
        Schema::create('booking_user', function (Blueprint $table) {
            $table->foreignId('booking_id')->index();
            $table->foreignId('user_id')->index();
            $table->enum('status', ['accepted', 'tentative', 'declined', 'needs-action'])
                ->default('needs-action');
            $table->enum('role', ['lead-instructor'])->nullable()->default(null);
            $table->timestamps(6);

            $table->unique(['booking_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_user');
    }
};
