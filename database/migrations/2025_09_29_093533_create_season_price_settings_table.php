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
        Schema::create('season_price_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('office_id')->constrained('offices')->cascadeOnDelete();
            $table->date('target_date');
            $table->integer('season_price')->nullable();
            $table->timestamps();
            $table->softDeletes();

			// 論理削除されていれば NULL， されていなければ 1 になる生成列を定義
            $table->boolean('is_active')->nullable()->storedAs('case when deleted_at is null then 1 else null end');
            $table->unique(['office_id', 'target_date', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('season_price_settings');
    }
};
