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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('office_id')->constrained('offices')->restrictOnDelete();
            $table->smallInteger('status')->default(1);
            $table->string('member_code', 50);
            $table->foreignId('member_type_id')->nullable()->constrained('member_types')->nullOnDelete();
            $table->string('name', 255);
            $table->string('kana', 255);
            $table->string('en_name', 255)->nullable();
            $table->string('zip', 9)->nullable();
            $table->string('address1', 255)->nullable();
            $table->string('address2', 255)->nullable();
            $table->string('tel', 15);
            $table->string('email', 255)->nullable();
            $table->string('line_id', 255)->nullable();
            $table->string('line_account', 255)->nullable();
            $table->string('line_email', 255)->nullable();
            $table->string('image_url', 255)->nullable();
            $table->string('password', 255)->nullable();
            $table->integer('used_num')->default(0);
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
        Schema::dropIfExists('members');
    }
};
