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
        Schema::create('coupons', function (Blueprint $table) {
            $table->string('name', 255);
            $table->string('code', 255)->nullable();
            $table->smallInteger('discount_amount');
            $table->tinyInteger('discount_type')->comment('1：円、2：%');
            $table->integer('good_category_id');
            $table->tinyInteger('limit_flg')->comment('0：1回、1：複数回');
            $table->tinyInteger('combination_flg')->comment('0：不可、1：可');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
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
        Schema::dropIfExists('coupons');
    }
};
