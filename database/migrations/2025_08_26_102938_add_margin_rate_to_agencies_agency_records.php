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
        Schema::table('agencies', function (Blueprint $table) {
            $table->smallInteger('margin_rate')->nullable()->default(0)->after('campaign_image');
        });
        Schema::table('agency_records', function (Blueprint $table) {
            $table->smallInteger('margin_rate')->nullable()->default(0)->after('coupon_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agencies', function (Blueprint $table) {
            $table->dropColumn('margin_rate');
        });
        Schema::table('agency_records', function (Blueprint $table) {
            $table->dropColumn('margin_rate');
        });
    }
};
