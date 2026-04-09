<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('sentences', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('sound_id');
        $table->text('subject_sentence');
        $table->text('correct_sentence');
        $table->time('start_time',7);
        $table->time('end_time',7);
        $table->timestamps();

        $table->foreign('sound_id')->references('id')->on('sounds')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sentences');
    }
};
