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
        Schema::create('payment_goods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->constrained('payments')->restrictOnDelete();
            $table->foreignId('good_category_id')->constrained('good_categories')->restrictOnDelete();
            $table->foreignId('deal_good_id')->constrained('deal_goods')->restrictOnDelete();
            $table->string('name');
            $table->integer('price')->default(0);
            $table->integer('tax')->nullable();
            $table->tinyInteger('tax_type');
            $table->integer('num')->default(0);
            $table->integer('total_price')->default(0);
            $table->integer('total_tax')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_goods');
    }
};
