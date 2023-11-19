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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->date('start_on');
            $table->time('start_at');
            $table->time('end_at');
            $table->string('location');
            $table->string('group_name');
            $table->text('notes');
            $table->unsignedBigInteger('primary_instructor_id');
            $table->timestamps();

            // TODO
            // $table->foreign('primary_instructor_id')->references('id')->on('instructors');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
