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
        Schema::table('deals', function (Blueprint $table) {
            $table->boolean('sync_flg')->default(false)->after('remind_mail_sent_flg');
            $table->dateTime('synced_at')->nullable()->after('sync_flg');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('deals', function (Blueprint $table) {
            $table->dropColumn('sync_flg');
            $table->dropColumn('synced_at');
        });
    }
};
