<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->string('name')
                ->default('Pointer')
                ->nullable();
            $table->string('meta')
                ->default('Meta')
                ->nullable();
            $table->text('description')
                ->nullable();

            $table->float('top')
                ->default(0)
                ->comment('css vmin unit');
            $table->string('left')
                ->float(0)
                ->comment('css vmin unit');

            $table->unsignedInteger('space_id');
            $table->foreign('space_id')
                ->references('id')
                ->on('spaces')
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
