<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddServerIdFieldToServersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('servers', function (Blueprint $table) {
            $table->string('server_id')->nullable()->after('name')->index();
            $table->dropColumn(['ip_address', 'game_port', 'br_index']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('servers', function (Blueprint $table) {
            $table->dropColumn(['server_id']);
            $table->string('ip_address')->after('country');
            $table->integer('game_port')->after('ip_address');
            $table->string('br_index')->after('os');
        });
    }
}
