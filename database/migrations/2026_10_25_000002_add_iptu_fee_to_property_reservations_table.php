<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('property_reservations', function (Blueprint $table) {
            $table->decimal('iptu_fee', 10, 2)->default(0)->after('cleaning_fee');
        });
    }

    public function down(): void
    {
        Schema::table('property_reservations', function (Blueprint $table) {
            $table->dropColumn('iptu_fee');
        });
    }
};
