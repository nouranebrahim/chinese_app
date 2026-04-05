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
        Schema::create('subject_word_pronounciations', function (Blueprint $table) {
            $table->id();
            $table->string('subject_word');
            $table->unsignedBigInteger('word_time_id');
            $table->foreign('word_time_id')->references('id')->on('word_times')->onDelete('cascade');

            $table->unsignedBigInteger('subject_sentence_id');
            $table->foreign('subject_sentence_id')->references('id')->on('subject_sentences')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subject_word_pronounciations');
    }
};
