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
        Schema::create('property_reservations', function (Blueprint $table) {
            $table->id();

            $table->unsignedInteger('property_id');

            $table->foreign('property_id')
                ->references('id')
                ->on('properties')
                ->onDelete('cascade');

            $table->unsignedInteger('user_id');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            // Dados do hóspede (caso não esteja logado)
            $table->string('guest_name')->nullable();
            $table->string('guest_email')->nullable();
            $table->string('guest_phone')->nullable();

            $table->date('check_in');
            $table->date('check_out');
            $table->integer('nights');

            $table->decimal('daily_total', 10, 2);
            $table->decimal('cleaning_fee', 10, 2)->default(0);
            $table->decimal('total_value', 10, 2);

            $table->enum('origin', ['admin', 'site']);
            $table->enum('status', ['pending', 'confirmed', 'cancelled']);

            $table->timestamps();

            $table->index(['property_id', 'check_in', 'check_out']);
            $table->index(['status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_reservations');
    }
};
