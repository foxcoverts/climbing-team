<?php

use App\Models\UserAccreditation;
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
        UserAccreditation::where('accreditation', 'permit-holder')->delete();

        Schema::table('user_accreditations', function (Blueprint $table) {
            $table->enum('accreditation', [
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
        Schema::table('user_accreditations', function (Blueprint $table) {
            $table->enum('accreditation', [
                'manage-bookings',
                'manage-qualifications',
                'manage-users',
                'permit-holder',
            ])->change();
        });
    }
};
