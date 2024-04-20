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
        Schema::create('good_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->tinyInteger('type')->comment('1：出庫までに作業が必要、2：出庫までに作業が不要');
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
        Schema::dropIfExists('good_categories');
    }
};
