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
            $table->string('flight_no', 255)->nullable()->after('arr_flight_id');
            $table->foreignId('airline_id')->nullable()->after('flight_no')->constrained('airlines')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('deals', function (Blueprint $table) {
            $table->dropColumn('flight_no');
            $table->dropConstrainedForeignId('airline_id');
        });
    }
};
