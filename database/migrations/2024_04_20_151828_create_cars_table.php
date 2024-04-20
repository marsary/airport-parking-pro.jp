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
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_maker_id')->constrained('car_makers')->restrictOnDelete();
            $table->string('name', 255);
            $table->string('kana', 255);
            $table->smallInteger('sort')->default(0);
            $table->smallInteger('size_type')->nullable()->default(0)->comment('1:普通車, 2:大型車');
            $table->text('memo')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
