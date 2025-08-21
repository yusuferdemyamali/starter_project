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
        // ABOUTS TABLE ADDITIONAL INDEXES
        Schema::table('abouts', function (Blueprint $table) {
            $table->index(['is_active', 'order'], 'abouts_active_order_idx');
            $table->index('section', 'abouts_section_idx');
            $table->index('is_active', 'abouts_active_idx');
            $table->index('order', 'abouts_order_idx');
        });

        // FAQS TABLE ADDITIONAL INDEXES
        Schema::table('faqs', function (Blueprint $table) {
            $table->index('category', 'faqs_category_idx');
            $table->index(['category', 'is_active'], 'faqs_category_active_idx');
        });

        // GALLERIES TABLE ADDITIONAL INDEXES
        Schema::table('galleries', function (Blueprint $table) {
            $table->index(['is_active'], 'galleries_active_idx');
            $table->index(['user_id', 'is_active'], 'galleries_user_active_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // ABOUTS TABLE INDEXES DROP
        Schema::table('abouts', function (Blueprint $table) {
            $table->dropIndex('abouts_active_order_idx');
            $table->dropIndex('abouts_section_idx');
            $table->dropIndex('abouts_active_idx');
            $table->dropIndex('abouts_order_idx');
        });

        // FAQS TABLE INDEXES DROP
        Schema::table('faqs', function (Blueprint $table) {
            $table->dropIndex('faqs_category_idx');
            $table->dropIndex('faqs_category_active_idx');
        });

        // GALLERIES TABLE INDEXES DROP
        Schema::table('galleries', function (Blueprint $table) {
            $table->dropIndex('galleries_active_idx');
            $table->dropIndex('galleries_user_active_idx');
        });
    }
};
