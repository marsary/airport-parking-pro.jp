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
        Schema::create('zipcodes', function (Blueprint $table) {
            $table->id();
            $table->string('jis_code', 5);
            $table->string('zip_old', 5);
            $table->string('zip', 7);
            $table->string('prefectures_kana', 255)->nullable();
            $table->string('city_kana', 255)->nullable();
            $table->string('town_kana', 255)->nullable();
            $table->string('prefectures', 255)->nullable();
            $table->string('city', 255)->nullable();
            $table->string('town', 255)->nullable();
            $table->tinyInteger('flag1')->nullable()->comment('「1」は該当、「0」は該当せず');
            $table->tinyInteger('flag2')->nullable()->comment('「1」は該当、「0」は該当せず');
            $table->tinyInteger('flag3')->nullable()->comment('「1」は該当、「0」は該当せず');
            $table->tinyInteger('flag4')->nullable()->comment('「1」は該当、「0」は該当せず');
            $table->tinyInteger('flag5')->nullable()->comment('「0」は変更なし、「1」は変更あり、「2」廃止（廃止データのみ使用）');
            $table->tinyInteger('flag6')->nullable()->comment('「0」は変更なし、「1」市政・区政・町政・分区・政令指定都市施行、「2」住居表示の実施、「3」区画整理、「4」郵便区調整等、「5」訂正、「6」廃止（廃止データのみ使用）');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zipcodes');
    }
};
