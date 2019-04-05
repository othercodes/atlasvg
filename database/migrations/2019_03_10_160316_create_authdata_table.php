<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuthdataTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('authdata', function (Blueprint $table) {
            $table->increments('id');
            $table->string('accessToken')->nullable();
            $table->string('refreshToken')->nullable();
            $table->unsignedInteger('tokenExpires')->nullable();
            $table->string('userName')->nullable();
            $table->string('userEmail')->nullable();
            $table->unsignedInteger('building_id')->default(0);
            $table->foreign('building_id')
                ->references('id')
                ->on('buildings')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('authdata');
    }
}