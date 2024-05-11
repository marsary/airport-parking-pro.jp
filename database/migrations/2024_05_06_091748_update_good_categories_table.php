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
        Schema::table('good_categories', function (Blueprint $table) {
            $table->foreignId('office_id')->after('id')->constrained('offices')->restrictOnDelete();
            $table->boolean('regi_display_flag')->after('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('good_categories', function (Blueprint $table) {
            $table->dropConstrainedForeignId('office_id');
            $table->dropColumn('regi_display_flag');
        });
    }
};
