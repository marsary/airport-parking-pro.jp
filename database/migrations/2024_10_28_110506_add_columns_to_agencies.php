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
            $table->foreignId('office_id')->after('id')->constrained('offices')->restrictOnDelete();
            $table->string('branch', 100)->nullable()->after('keyword');
            $table->boolean('monthly_fixed_cost_flag')->nullable()->after('receipt_issue')->comment('0：支払わない、1：支払う');
            $table->integer('monthly_fixed_cost')->nullable()->after('monthly_fixed_cost_flag');
            $table->boolean('incentive_flag')->nullable()->after('monthly_fixed_cost')->comment('0：支払わない、1：支払う');
            $table->smallInteger('incentive')->nullable()->after('incentive_flag');
            $table->string('banner_comment_set')->nullable()->after('incentive');
            $table->string('title_set')->nullable()->after('banner_comment_set');
            $table->string('logo_image')->nullable()->after('title_set');
            $table->string('campaign_image')->nullable()->after('logo_image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agencies', function (Blueprint $table) {
            $table->dropConstrainedForeignId('office_id');
            $table->dropColumn('branch');
            $table->dropColumn('monthly_fixed_cost_flag');
            $table->dropColumn('monthly_fixed_cost');
            $table->dropColumn('incentive_flag');
            $table->dropColumn('incentive');
            $table->dropColumn('banner_comment_set');
            $table->dropColumn('title_set');
            $table->dropColumn('logo_image');
            $table->dropColumn('campaign_image');
        });
    }
};
