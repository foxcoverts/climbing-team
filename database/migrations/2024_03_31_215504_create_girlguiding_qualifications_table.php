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
        Schema::create('girlguiding_qualifications', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('scheme');
            $table->unsignedTinyInteger('level');
            $table->timestamps(6);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('girlguiding_qualifications');
    }
};
