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
        Schema::table('deals', function (Blueprint $table) {
            if (env('DB_CONNECTION') !== 'sqlite') {
                $table->dropForeign(['arr_flight_id']);
            }
            $table->foreign('arr_flight_id')->references('id')->on('arrival_flights')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
