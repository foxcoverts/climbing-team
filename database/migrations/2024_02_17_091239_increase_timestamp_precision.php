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
        Schema::table('users', function (Blueprint $table): void {
            $table->timestamp('email_verified_at', 6)->nullable()->change();
            $this->changeTimestamps($table, 6);
        });

        Schema::table('password_reset_tokens', function (Blueprint $table): void {
            $table->timestamp('created_at', 6)->nullable()->change();
        });

        Schema::table('failed_jobs', function (Blueprint $table): void {
            $table->timestamp('failed_at', 6)->useCurrent()->change();
        });

        Schema::table('personal_access_tokens', function (Blueprint $table): void {
            $table->timestamp('last_used_at', 6)->nullable()->change();
            $table->timestamp('expires_at', 6)->nullable()->change();
            $this->changeTimestamps($table, 6);
        });

        Schema::table('bookings', function (Blueprint $table): void {
            $table->timestamp('start_at', 6)->change();
            $table->timestamp('end_at', 6)->change();
            $table->softDeletes(precision: 6)->change();
            $this->changeTimestamps($table, 6);
        });

        Schema::table('booking_user', function (Blueprint $table): void {
            $this->changeTimestamps($table, 6);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->timestamp('email_verified_at')->nullable()->change();
            $this->changeTimestamps($table);
        });

        Schema::table('password_reset_tokens', function (Blueprint $table): void {
            $table->timestamp('created_at')->nullable()->change();
        });

        Schema::table('failed_jobs', function (Blueprint $table): void {
            $table->timestamp('failed_at')->useCurrent()->change();
        });

        Schema::table('personal_access_tokens', function (Blueprint $table): void {
            $table->timestamp('last_used_at')->nullable()->change();
            $table->timestamp('expires_at')->nullable()->change();
            $this->changeTimestamps($table);
        });

        Schema::table('bookings', function (Blueprint $table): void {
            $table->timestamp('start_at')->change();
            $table->timestamp('end_at')->change();
            $table->softDeletes()->change();
            $this->changeTimestamps($table);
        });

        Schema::table('booking_user', function (Blueprint $table): void {
            $this->changeTimestamps($table);
        });
    }

    /**
     * Change the timestamp columns already on a table.
     *
     * @param Blueprint $table
     * @param integer $precision
     * @return void
     */
    protected function changeTimestamps(Blueprint $table, int $precision = 0): void
    {
        $table->timestamp('created_at', $precision)->nullable()->change();
        $table->timestamp('updated_at', $precision)->nullable()->change();
    }
};
