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
        // ABOUTS - section, is_active, order fields ekleme
        Schema::table('abouts', function (Blueprint $table) {
            $table->string('section')->nullable()->after('image');
            $table->boolean('is_active')->default(true)->after('section');
            $table->integer('order')->default(0)->after('is_active');
        });

        // FAQS - category field ekleme
        Schema::table('faqs', function (Blueprint $table) {
            $table->string('category')->nullable()->after('answer');
        });

        // GALLERIES - user_id, is_active fields ekleme
        Schema::table('galleries', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('slug');
            $table->boolean('is_active')->default(true)->after('user_id');
            
            // Foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // ABOUTS - fields drop
        Schema::table('abouts', function (Blueprint $table) {
            $table->dropColumn(['section', 'is_active', 'order']);
        });

        // FAQS - category field drop
        Schema::table('faqs', function (Blueprint $table) {
            $table->dropColumn('category');
        });

        // GALLERIES - fields drop
        Schema::table('galleries', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'is_active']);
        });
    }
};
