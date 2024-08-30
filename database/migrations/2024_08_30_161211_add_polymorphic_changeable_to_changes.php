<?php

use App\Models\Booking;
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
        Schema::table('changes', function (Blueprint $table) {
            $table->foreignUlid('booking_id')->change(); // no constrained or cascade
        });
        Schema::table('changes', function (Blueprint $table) {
            $table->renameColumn('booking_id', 'changeable_id');
            $table->string('changeable_type')->after('changeable_id');
        });

        DB::table('changes')
            ->update(['changeable_type' => Booking::class]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('changes')
            ->whereNot(['changeable_type' => Booking::class])
            ->delete();

        Schema::table('changes', function (Blueprint $table) {
            $table->renameColumn('changeable_id', 'booking_id');
            $table->dropColumn('changeable_type');
        });
    }
};
