<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RefactorNodeAccessLevelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('node_access_levels', function (Blueprint $table) {
            $table->renameColumn('access_flag', 'flag');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('node_access_levels', function (Blueprint $table) {
            $table->renameColumn('flag', 'access_flag');
        });
    }
}
