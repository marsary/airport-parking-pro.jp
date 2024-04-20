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
        Schema::create('offices', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('short_name', 255);
            $table->foreignId('airport_id')->constrained('airports')->restrictOnDelete();
            $table->string('receipt_issuer', 9);
            $table->string('receipt_address', 255);
            $table->string('receipt_tel', 15);
            $table->integer('max_car_num');
            $table->string('start_time', 5)->nullable();
            $table->string('end_time', 5)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offices');
    }
};
