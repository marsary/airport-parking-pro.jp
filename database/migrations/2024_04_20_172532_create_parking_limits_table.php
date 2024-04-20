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
        Schema::create('parking_limits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('office_id')->constrained('offices')->cascadeOnDelete();
            $table->date('target_date');
            $table->smallInteger('max_parking_num')->nullable();
            $table->smallInteger('load_limit')->nullable();
            $table->smallInteger('unload_limit')->nullable();
            $table->smallInteger('at_closing_time')->nullable();
            $table->smallInteger('per_fifteen_munites')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parking_limits');
    }
};
