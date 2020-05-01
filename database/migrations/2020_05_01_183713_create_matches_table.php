<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('matches', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('server_id');
            $table->string('team1_name');
            $table->string('team2_name');
            $table->string('map');
            $table->timestamps();
        });

        Schema::table('matches', function (Blueprint $table) {
            $table->foreign('server_id')
                ->references('id')
                ->on('servers')
                ->onDelete('cascade');
        });

        Schema::create('match_player', function (Blueprint $table) {
            $table->unsignedInteger('match_id');
            $table->unsignedInteger('player_id');
            $table->integer('score')->default(0);
            $table->integer('kills')->default(0);
            $table->integer('deaths')->default(0);
            $table->string('team')->nullable();
            $table->timestamps();
            $table->primary(['match_id', 'player_id']);
        });

        Schema::table('match_player', function (Blueprint $table) {
            $table->foreign('match_id')
                ->references('id')
                ->on('matches')
                ->onDelete('cascade');

            $table->foreign('player_id')
                ->references('id')
                ->on('players')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('match_player');
        Schema::dropIfExists('matches');
    }
}
