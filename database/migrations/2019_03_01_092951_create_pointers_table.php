<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePointersTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        Schema::create('pointers', function (Blueprint $table) {
            $table->increments('id')
                ->unsigned();

            $table->string('name');
            $table->string('meta');
            $table->text('description');

            $table->string('top')
                ->comment('css vmin unit');
            $table->string('left')
                ->comment('css vmin unit');

            $table->unsignedInteger('level_id');
            $table->foreign('level_id')
                ->references('id')
                ->on('levels')
                ->onDelete('cascade');

            $table->unsignedInteger('category_id');
            $table->foreign('category_id')
                ->references('id')
                ->on('categories')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pointers');
    }
}
