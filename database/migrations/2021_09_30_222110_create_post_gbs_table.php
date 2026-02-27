<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostGbsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_gb', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_img')->default(0);
            $table->unsignedInteger('post');
            $table->string('path');
            $table->boolean('cover')->nullable();

            $table->timestamps();

            $table->foreign('post')->references('id')->on('posts')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('post_gb');
    }
}
