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
        Schema::create('goods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('good_category_id')->constrained('good_categories')->restrictOnDelete();
            $table->tinyInteger('status')->default(1);
            $table->string('name', 255);
            $table->string('abbreviation', 255)->nullable();
            $table->integer('price');
            $table->tinyInteger('tax_type');
            $table->tinyInteger('one_day_flg')->default(0);
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('sort')->default(0);
            $table->text('memo')->nullable();
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
        Schema::dropIfExists('goods');
    }
};
