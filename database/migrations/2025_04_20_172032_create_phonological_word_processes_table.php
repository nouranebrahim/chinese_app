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
        Schema::create('phonological_word_processes', function (Blueprint $table) {
            $table->id();
            $table->string('phonological_word_process');

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
        Schema::dropIfExists('phonological_word_processes');
    }
};
