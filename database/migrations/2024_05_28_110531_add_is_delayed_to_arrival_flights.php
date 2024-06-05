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
        Schema::table('arrival_flights', function (Blueprint $table) {
            $table->boolean('is_delayed')->default(false)->after('arrive_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('arrival_flights', function (Blueprint $table) {
            $table->dropColumn('is_delayed');
        });
    }
};
