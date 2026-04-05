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
    Schema::table('users', function (Blueprint $table) {
        $table->enum('level', ['novice', 'intermediate','advanced'])->after('name');
        $table->enum('gender', ['male', 'female'])->after('level');
        $table->string('email')->nullable()->change();
        $table->string('password')->nullable()->change();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['level', 'gender']);
        $table->string('email')->nullable(false)->change();
        $table->string('password')->nullable(false)->change();
    });
}
};
