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
        Schema::create('dynamic_pricings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('office_id')->constrained('offices')->cascadeOnDelete();
            $table->string('name', 255);
            $table->integer('p10')->nullable();
            $table->integer('p20')->nullable();
            $table->integer('p30')->nullable();
            $table->integer('p40')->nullable();
            $table->integer('p50')->nullable();
            $table->integer('p60')->nullable();
            $table->integer('p70')->nullable();
            $table->integer('p80')->nullable();
            $table->integer('p90')->nullable();
            $table->integer('p100')->nullable();
            $table->integer('p110')->nullable();
            $table->integer('p120')->nullable();
            $table->integer('p130')->nullable();
            $table->integer('p131')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dynamic_pricings');
    }
};
