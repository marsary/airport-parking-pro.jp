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
        Schema::create('agency_records', function (Blueprint $table) {
            $table->id();

            $table->foreignId('office_id')->constrained('offices')->restrictOnDelete();
            $table->foreignId('agency_id')->constrained('agencies')->cascadeOnDelete();
            $table->string('agency_name', 100)->nullable();
            $table->string('receipt_code', 50);
            $table->string('member_code', 50);
            $table->foreignId('deal_id')->nullable()->constrained('deals')->cascadeOnDelete();
            $table->string('reserve_name')->nullable();
            $table->string('reserve_name_kana')->nullable();
            $table->date('load_date')->nullable();
            $table->date('unload_date')->nullable();
            $table->date('unload_date_plan')->nullable();
            $table->time('unload_time_plan')->nullable();
            $table->integer('num_days')->nullable();
            $table->integer('num_days_plan')->nullable();
            $table->string('airline_name')->nullable();
            $table->string('dep_airport_name')->nullable();
            $table->string('flight_name', 50)->nullable();
            $table->date('arrive_date')->nullable();
            $table->time('arrive_time')->nullable();
            $table->string('car_name')->nullable();
            $table->string('car_maker_name')->nullable();
            $table->string('car_color_name')->nullable();
            $table->smallInteger('car_number')->nullable();
            $table->integer('dt_price_load')->nullable();
            $table->integer('price')->nullable();
            $table->integer('base_price')->nullable();
            $table->integer('pay_not_real')->nullable();
            $table->boolean('has_voucher')->nullable();
            $table->string('coupon_name')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agency_records');
    }
};
