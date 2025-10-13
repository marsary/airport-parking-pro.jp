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
        Schema::table('parking_limits', function (Blueprint $table) {
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
        Schema::table('parking_limits', function (Blueprint $table) {
            $table->dropUnique(['office_id', 'target_date', 'is_active']);
            $table->dropColumn('is_active');
        });
    }
};
