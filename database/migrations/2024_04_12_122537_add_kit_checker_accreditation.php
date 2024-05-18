<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('user_accreditations', function (Blueprint $table) {
            $table->enum('accreditation', [
                'kit-checker',
                'manage-bookings',
                'manage-qualifications',
                'manage-users',
            ])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('user_accreditations')->where('accreditation', 'kit-checker')->delete();

        Schema::table('user_accreditations', function (Blueprint $table) {
            $table->enum('accreditation', [
                'manage-bookings',
                'manage-qualifications',
                'manage-users',
            ])->change();
        });
    }
};
