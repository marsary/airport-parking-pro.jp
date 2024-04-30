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
        Schema::create('departure_flights', function (Blueprint $table) {
            $table->id();
            $table->string('flight_no', 255)->nullable();
            $table->string('name', 255)->nullable();
            $table->foreignId('dep_airport_id')->nullable()->constrained('airports', 'id')->nullOnDelete();
            $table->foreignId('arr_airport_id')->nullable()->constrained('airports', 'id')->nullOnDelete();
            $table->foreignId('airline_id')->nullable()->constrained('airlines')->nullOnDelete();
            $table->foreignId('terminal_id')->nullable()->constrained('airport_terminals', 'id')->nullOnDelete();
            $table->date('depart_date')->nullable();
            $table->time('depart_time')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departure_flights');
    }
};
