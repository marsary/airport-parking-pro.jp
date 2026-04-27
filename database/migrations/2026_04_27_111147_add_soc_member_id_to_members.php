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
        Schema::table('members', function (Blueprint $table) {
            $table->bigInteger('soc_member_id')->nullable()->after('member_code');
            $table->boolean('soc_member_flg')->default(false)->after('soc_member_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn('soc_member_id');
            $table->dropColumn('soc_member_flg');
        });
    }
};
