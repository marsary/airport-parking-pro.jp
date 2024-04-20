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
        Schema::create('agency_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('office_id')->nullable()->constrained('offices')->nullOnDelete();
            $table->foreignId('agency_id')->constrained('agencies')->cascadeOnDelete();
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('base_price')->nullable();
            $table->integer('d1');
            $table->integer('d2');
            $table->integer('d3');
            $table->integer('d4');
            $table->integer('d5');
            $table->integer('d6');
            $table->integer('d7');
            $table->integer('d8');
            $table->integer('d9');
            $table->integer('d10');
            $table->integer('d11');
            $table->integer('d12');
            $table->integer('d13');
            $table->integer('d14');
            $table->integer('d15');
            $table->integer('price_per_day');
            $table->integer('lsize_rate')->default(200);
            $table->boolean('no_discount_flg')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agency_prices');
    }
};
