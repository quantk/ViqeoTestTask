<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImageResizesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('image_resizes', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->integer('width')->nullable();
            $table->integer('height')->nullable();
            $table->string('original_path');
            $table->string('resized_path')->nullable();
            $table->dateTime('destroy_time')->nullable();
            $table->smallInteger('status');
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
        Schema::dropIfExists('image_resizes');
    }
}
