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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_code', 50);
            $table->dateTime('payment_date');
            $table->foreignId('cash_register_id')->nullable()->constrained('cash_registers')->nullOnDelete();
            $table->foreignId('office_id')->constrained('offices')->restrictOnDelete();
            $table->foreignId('deal_id')->constrained('deals')->restrictOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('user_name');
            $table->foreignId('member_id')->constrained('members')->restrictOnDelete();
            $table->date('load_date');
            $table->date('unload_date_plan')->default('9999-1-1');
            $table->date('unload_date')->nullable();
            $table->integer('days')->nullable()->default(0);

            $table->integer('price')->nullable()->default(0);
            $table->integer('goods_total_price')->nullable()->default(0);
            $table->integer('total_price')->nullable()->default(0);
            $table->integer('total_tax')->nullable()->default(0);
            $table->integer('demand_price')->nullable()->default(0);
            $table->integer('total_pay')->nullable()->default(0);
            $table->integer('cash_enter')->nullable()->default(0);
            $table->integer('cash_change')->nullable()->default(0);
            $table->integer('cash_add')->nullable()->default(0);

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
        Schema::dropIfExists('payments');
    }
};
