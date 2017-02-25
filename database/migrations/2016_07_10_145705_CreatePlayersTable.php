<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlayersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('players', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('clan_id')->unsigned()->nullable()->index();
            $table->integer('server_id')->unsigned()->nullable()->index();
            $table->integer('pid')->unsigned()->index();
            $table->string('name')->index();
            $table->string('slug')->nullable()->index();
            $table->integer('total_score')->unsigned()->default(0);
            $table->integer('total_kills')->unsigned()->default(0);
            $table->integer('total_deaths')->unsigned()->default(0);
            $table->integer('last_score')->unsigned()->default(0);
            $table->integer('last_kills')->unsigned()->default(0);
            $table->integer('last_deaths')->unsigned()->default(0);
            $table->integer('games_played')->unsigned()->default(1);
            $table->integer('minutes_played')->unsigned()->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('players');
    }
}
