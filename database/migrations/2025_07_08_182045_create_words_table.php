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
        Schema::create('words', function (Blueprint $table) {
            $table->id();
            $table->text('subject_pronunciation');
            $table->text('arabic_word');
            $table->text('correct_pronunciation');
            $table->text('phonological_errors')->nullable();
            $table->text('notes')->nullable();
            $table->time('start_time',7);
            $table->time('end_time',7);
            $table->unsignedBigInteger('sentence_id')->nullable();
            $table->unsignedBigInteger('sound_id');
            $table->timestamps();
    
            $table->foreign('sentence_id')->references('id')->on('sentences')->onDelete('cascade');
            $table->foreign('sound_id')->references('id')->on('sounds')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('words');
    }
};
