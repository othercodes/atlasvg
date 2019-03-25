<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRefToBuildingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('authdata', function (Blueprint $table) {
            $table->unsignedInteger('building_id')->default(0);
            $table->foreign('building_id')
                ->references('id')
                ->on('buildings')
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
        Schema::table('authdata', function (Blueprint $table) {
            # sqlite doesn't allow dropping foreign keys, since it's MVP skipping cleaning up on rollback
            #$table->dropForeign('authdata_building_id_foreign');
            #$table->dropColumn('building_id');
        });
    }
}
