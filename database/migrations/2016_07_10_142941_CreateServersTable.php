<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->index();
            $table->string('country');
            $table->string('ip_address');
            $table->integer('game_port');
            $table->string('last_map');
            $table->integer('num_players')->index();
            $table->integer('max_players');
            $table->integer('reserved_slots');
            $table->string('os');
            $table->string('br_index');
            $table->string('br_download');
            $table->text('server_text');
            $table->string('server_logo');
            $table->string('community_website');
            $table->string('team1_name')->nullable();
            $table->integer('team1_score')->default(0);
            $table->integer('team1_kills')->default(0);
            $table->integer('team1_deaths')->default(0);
            $table->string('team2_name')->nullable();
            $table->integer('team2_score')->default(0);
            $table->integer('team2_kills')->default(0);
            $table->integer('team2_deaths')->default(0);
            $table->integer('total_score')->default(0);
            $table->integer('total_kills')->default(0);
            $table->integer('total_deaths')->default(0);
            $table->integer('games_played')->default(1);
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
        Schema::drop('servers');
    }
}
