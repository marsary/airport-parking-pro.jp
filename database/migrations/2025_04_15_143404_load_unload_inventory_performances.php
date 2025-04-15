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
        Schema::create('load_unload_inventory_performances', function (Blueprint $table) {
            $table->id();
            $table->date('target_date');
            $table->integer('load_quantity')->default(0);
            $table->integer('unload_quantity')->default(0);
            $table->integer('stock_quantity')->default(0);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('load_unload_inventory_performances');
    }
};
