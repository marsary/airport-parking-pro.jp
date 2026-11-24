<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * 二重送信対策：予約コードの重複登録をDBレベルで防止する
     * ※実行前に deals.reserve_code の重複データ（論理削除済みを含む）を解消しておくこと
     */
    public function up(): void
    {
        Schema::table('deals', function (Blueprint $table) {
            $table->unique('reserve_code');
        });
    }

    public function down(): void
    {
        Schema::table('deals', function (Blueprint $table) {
            $table->dropUnique(['reserve_code']);
        });
    }
};
