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
        Schema::table('payments', function (Blueprint $table) {
            $table->date('load_date')->nullable()->change();
            $table->date('unload_date_plan')->nullable()->default('9999-1-1')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->date('load_date')->change();
            $table->date('unload_date_plan')->default('9999-1-1')->change();
        });
    }
};
