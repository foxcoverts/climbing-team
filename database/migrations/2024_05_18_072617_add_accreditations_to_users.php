<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add accreditations column to users
        Schema::table('users', function (Blueprint $table) {
            $table->set('accreditations', [
                'kit-checker',
                'manage-bookings',
                'manage-qualifications',
                'manage-users',
            ])->default('');
        });

        // Migrate data from user_accreditations table to new column
        $user_accreditations = DB::table('user_accreditations')->get()->groupBy('user_id');
        foreach ($user_accreditations as $user_id => $accreditations) {
            DB::table('users')
                ->where('id', $user_id)
                ->update([
                    'accreditations' => $accreditations->pluck('accreditation')->join(','),
                ]);
        }

        // Remove user_accreditations table
        Schema::drop('user_accreditations');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restore user_accreditations table
        Schema::create('user_accreditations', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('user_id')->constrained()->cascadeOnDelete();
            $table->enum('accreditation', [
                'kit-checker',
                'manage-bookings',
                'manage-qualifications',
                'manage-users',
            ]);
            $table->timestamps();

            $table->unique(['user_id', 'accreditation'], 'user_accreditation');
        });

        // Migrate data from user.accreditations to user_accreditations table
        $user_accreditations = [];
        $users_with_accreditations = DB::table('users')->whereNot('accreditations', '')->select(['id', 'accreditations'])->get();
        foreach ($users_with_accreditations as $user) {
            $accreditations = explode(',', $user->accreditations);
            foreach ($accreditations as $accreditation) {
                $user_accreditations[] = [
                    'id' => (string) Str::ulid(),
                    'accreditation' => $accreditation,
                    'user_id' => $user->id,
                ];
            }
        }
        DB::table('user_accreditations')->insert($user_accreditations);

        // Remove user.accredtations column
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('accreditations');
        });
    }
};
