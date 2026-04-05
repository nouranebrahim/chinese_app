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
        Schema::table('words', function (Blueprint $table) {
            $table->time('start_time', 6)->change();
            $table->time('end_time', 6)->change();
        });

        Schema::table('sentences', function (Blueprint $table) {
            $table->time('start_time', 6)->change();
            $table->time('end_time', 6)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('words', function (Blueprint $table) {
            $table->time('start_time')->change();
            $table->time('end_time')->change();
        });

        Schema::table('sentences', function (Blueprint $table) {
            $table->time('start_time')->change();
            $table->time('end_time')->change();
        });
    }
};
