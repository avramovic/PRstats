<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCountryFieldsToPlayersAndClans extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('players', function (Blueprint $table) {
            $table->string('country')->nullable()->after('slug');
        });
        Schema::table('clans', function (Blueprint $table) {
            $table->string('country')->nullable()->after('slug');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clans', function (Blueprint $table) {
            $table->dropColumn(['country']);
        });
        Schema::table('players', function (Blueprint $table) {
            $table->dropColumn(['country']);
        });
    }
}
