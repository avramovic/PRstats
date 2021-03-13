<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMapIdToMatches extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->unsignedInteger('map_id')->nullable()->after('map');
        });

        Schema::table('matches', function (Blueprint $table) {
            $table->foreign('map_id')
                ->references('id')
                ->on('maps')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->dropForeign(['map_id']);
            $table->dropColumn(['map_id']);
        });
    }
}
