<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->decimal('rental_value_couple', 10, 2)->nullable()->after('rental_value');
            $table->decimal('iptu_fee', 10, 2)->nullable()->after('cleaning_fee');
        });
    }

    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn(['rental_value_couple', 'iptu_fee']);
        });
    }
};
