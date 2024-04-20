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
        Schema::create('deals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('members')->cascadeOnDelete();
            $table->foreignId('office_id')->constrained('offices')->restrictOnDelete();
            $table->foreignId('agency_id')->nullable()->constrained('agencies')->nullOnDelete()->comment('予約経路としても使用');
            $table->smallInteger('status')->nullable()->default(1);
            $table->string('reserve_code', 50);
            $table->string('receipt_code', 50);
            $table->dateTime('reserve_date');
            $table->date('load_date')->nullable();
            $table->time('load_time')->nullable();
            $table->date('visit_date_plan')->nullable();
            $table->date('visit_time_plan')->nullable();
            $table->date('unload_date_plan')->nullable();
            $table->time('unload_time_plan')->nullable();
            $table->boolean('arrival_flg')->nullable()->comment('0：出庫日と同じ、1：出庫日と異なる');
            $table->date('unload_date')->nullable();
            $table->time('unload_time')->nullable();
            $table->integer('num_days')->nullable();
            $table->integer('num_members')->nullable()->default(1);
            $table->string('name', 255);
            $table->string('kana', 255);
            $table->string('zip', 8)->nullable();
            $table->string('tel', 15)->nullable();
            $table->string('email', 255)->nullable();
            $table->smallInteger('dsc_rate')->nullable()->default(0);
            $table->integer('price')->nullable()->default(0);
            $table->integer('tax')->nullable()->default(0);
            $table->integer('total_price')->nullable()->default(0);
            $table->integer('total_tax')->nullable()->default(0);
            $table->boolean('loaded_flg')->nullable();
            $table->foreignId('arr_flight_id')->nullable()->constrained('member_cars')->nullOnDelete();
            $table->foreignId('member_car_id')->constrained('member_cars')->restrictOnDelete();
            $table->string('receipt_address', 100)->nullable()->comment('受付時に発行する領収書の宛名（ない場合は顧客名を宛名とする）');
            $table->text('reserve_memo')->nullable();
            $table->text('reception_memo')->nullable();
            $table->text('remarks')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deals');
    }
};
