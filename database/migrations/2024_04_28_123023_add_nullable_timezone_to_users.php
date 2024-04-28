<?php

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
        Schema::table('users', function (Blueprint $table) {
            $table->string('timezone', 100)->nullable()->change();
        });
        User::where('timezone', 'UTC')->update(['timezone' => null]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        User::whereIsNull('timezone')->update(['timezone' => 'UTC']);
        Schema::table('users', function (Blueprint $table) {
            $table->string('timezone')->default('UTC')->change();
        });
    }
};
