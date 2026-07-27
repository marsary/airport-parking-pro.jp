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
        Schema::create('full_limit_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('office_id')->constrained('offices')->cascadeOnDelete();
            $table->date('target_date');
            $table->smallInteger('load_limit_symbol')->nullable();
            $table->smallInteger('unload_limit_symbol')->nullable();
            $table->smallInteger('cross_time_symbol')->nullable();
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
        Schema::dropIfExists('full_limit_settings');
    }
};
