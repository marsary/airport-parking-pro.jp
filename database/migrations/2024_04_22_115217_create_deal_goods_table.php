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
        Schema::create('deal_goods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('deal_id')->constrained('deals')->cascadeOnDelete();
            $table->foreignId('good_id')->constrained('goods')->restrictOnDelete();
            $table->integer('num')->default(0);
            $table->integer('total_price')->default(0);
            $table->integer('total_tax')->nullable()->default(0);
            $table->date('sales_date')->nullable();
            $table->date('return_date')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deal_goods');
    }
};
