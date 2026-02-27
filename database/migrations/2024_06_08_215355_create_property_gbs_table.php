<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('property_gbs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_img')->default(0);
            $table->unsignedInteger('property');
            $table->string('path');
            $table->boolean('cover')->nullable();
            $table->boolean('watermark')->nullable();
            $table->timestamps();

            $table->foreign('property')->references('id')->on('properties')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_gbs');
    }
};
