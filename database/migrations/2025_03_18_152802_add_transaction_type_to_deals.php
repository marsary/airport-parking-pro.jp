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
            $table->smallInteger('transaction_type')->default(1)->after('agency_id');
            $table->dateTime('reserve_date')->nullable()->change();
            $table->string('name', 255)->nullable()->change();
            $table->string('kana', 255)->nullable()->change();
            $table->unsignedBigInteger('member_car_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('deals', function (Blueprint $table) {
            $table->dropColumn('transaction_type');
            $table->dateTime('reserve_date')->change();
            $table->string('name', 255)->change();
            $table->string('kana', 255)->change();
            $table->unsignedBigInteger('member_car_id')->nullable(false)->change();
        });
    }
};
