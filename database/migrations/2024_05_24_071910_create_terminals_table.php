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
        Schema::create('terminals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('airport_id')->constrained('airports')->cascadeOnDelete();
            $table->foreignId('airport_terminal_id')->constrained('airport_terminals')->cascadeOnDelete();
            $table->string('name', 50)->nullable();
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
        Schema::dropIfExists('terminals');
    }
};
