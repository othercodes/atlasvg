<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spaces', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type');
            $table->float('data');
            $table->float('x')->default(0.0);
            $table->float('y')->default(0.0);
            $table->float('width')->default(0.0);
            $table->float('height')->default(0.0);
            $table->float('rx')->default(0.0);
            $table->float('ry')->default(0.0);
            $table->unsignedInteger('level_id');
            $table->foreign('level_id')
                ->references('id')
                ->on('levels')
                ->onDelete('cascade');

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
        Schema::dropIfExists('spaces');
    }
}
