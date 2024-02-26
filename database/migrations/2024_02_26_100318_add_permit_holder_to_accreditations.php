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
        Schema::table('user_accreditations', function (Blueprint $table) {
            $table->enum('accreditation', ['manage-bookings', 'manage-users', 'permit-holder'])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_accreditations', function (Blueprint $table) {
            $table->enum('accreditation', ['manage-bookings', 'manage-users'])->change();
        });
    }
};
