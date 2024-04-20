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
        Schema::create('airport_terminals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('airport_id')->constrained('airports')->cascadeOnDelete();
            $table->foreignId('airline_id')->constrained('airlines')->cascadeOnDelete();
            $table->integer('terminal_id');
            $table->tinyInteger('default_flg')->default(0);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('airport_terminals');
    }
};
