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
        Schema::create('correct_words', function (Blueprint $table) {
            $table->id();
            $table->string('correct_word');

            $table->unsignedBigInteger('correct_sentence_id');
            $table->foreign('correct_sentence_id')->references('id')->on('correct_sentences')->onDelete('cascade');

            $table->unsignedBigInteger('subject_word_id');
            $table->foreign('subject_word_id')->references('id')->on('subject_word_pronounciations')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('correct_words');
    }
};
