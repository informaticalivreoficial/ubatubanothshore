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
        Schema::create('property_seasons', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('property_id');

            $table->foreign('property_id')
                ->references('id')
                ->on('properties')
                ->onDelete('cascade');

            $table->string('label')->nullable(); // Ex: Alta Temporada
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('price_per_day', 10, 2);

            $table->timestamps();

            $table->index(['property_id', 'start_date', 'end_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_seasons');
    }
};
