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
            $table->string('accessToken');
            $table->string('refreshToken');
            $table->unsignedInteger('tokenExpires');
            $table->string('userName');
            $table->string('userEmail');
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