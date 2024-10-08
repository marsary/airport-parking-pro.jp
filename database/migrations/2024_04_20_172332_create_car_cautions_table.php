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
        Schema::create('car_cautions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('office_id')->constrained('offices')->restrictOnDelete();
            $table->string('name', 200);
            $table->smallInteger('sort')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_cautions');
    }
};
