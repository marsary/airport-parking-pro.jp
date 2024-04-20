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
        Schema::create('agencies', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('code', 100)->nullable();
            $table->string('zip', 8)->nullable();
            $table->string('address1', 100)->nullable();
            $table->string('address2', 100)->nullable();
            $table->string('tel', 16)->nullable();
            $table->text('keyword')->nullable();
            $table->string('department', 20)->nullable();
            $table->string('position', 20)->nullable();
            $table->string('person', 50)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('payment_site', 100)->nullable();
            $table->string('payment_destination', 100)->nullable();
            $table->text('memo')->nullable();
            $table->string('receipt_issue', 100)->nullable();
            $table->timestamps();

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agencies');
    }
};
