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
        Schema::create('car_size_price_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('office_id')->constrained('offices')->cascadeOnDelete();
            $table->foreignId('car_size_id')->constrained('car_sizes')->cascadeOnDelete();
            $table->decimal('rate', 4,2)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_size_price_rates');
    }
};
