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
        Schema::table('member_cars', function (Blueprint $table) {
            $table->foreignId('car_id')->nullable()->change();
            $table->foreignId('car_color_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('member_cars', function (Blueprint $table) {
            $table->foreignId('car_id')->nullable(false)->change();
            $table->foreignId('car_color_id')->nullable(false)->change();
        });
    }
};
