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
        Schema::table('parking_limits', function (Blueprint $table) {
            $table->dropColumn('max_parking_num');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('parking_limits', function (Blueprint $table) {
            $table->smallInteger('max_parking_num')->after('target_date')->nullable();
        });
    }
};
