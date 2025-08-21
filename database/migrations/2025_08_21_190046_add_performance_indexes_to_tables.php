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
        // BLOGS TABLE INDEXES
        Schema::table('blogs', function (Blueprint $table) {
            // Performans indexleri
            $table->index(['is_active', 'published_at'], 'blogs_active_published_idx');
            $table->index(['is_active', 'order'], 'blogs_active_order_idx');
            $table->index(['is_active', 'views'], 'blogs_active_views_idx');
            $table->index(['blog_category_id', 'is_active'], 'blogs_category_active_idx');
            $table->index('published_at', 'blogs_published_at_idx');
            $table->index('views', 'blogs_views_idx');
            $table->index('order', 'blogs_order_idx');
            $table->index('author', 'blogs_author_idx');
            $table->index('created_at', 'blogs_created_at_idx');
        });

        // BLOG_CATEGORIES TABLE INDEXES
        Schema::table('blog_categories', function (Blueprint $table) {
            $table->index('is_active', 'blog_categories_active_idx');
            $table->index('name', 'blog_categories_name_idx');
            $table->index('created_at', 'blog_categories_created_at_idx');
        });

        // PRODUCTS TABLE INDEXES
        Schema::table('products', function (Blueprint $table) {
            $table->index(['is_active', 'price'], 'products_active_price_idx');
            $table->index(['category_id', 'is_active'], 'products_category_active_idx');
            $table->index('name', 'products_name_idx');
            $table->index('price', 'products_price_idx');
            $table->index('created_at', 'products_created_at_idx');
        });

        // PRODUCT_CATEGORIES TABLE INDEXES
        Schema::table('product_categories', function (Blueprint $table) {
            $table->index(['is_active', 'sort_order'], 'product_categories_active_sort_idx');
            $table->index('name', 'product_categories_name_idx');
            $table->index('sort_order', 'product_categories_sort_idx');
        });

        // TEAMS TABLE INDEXES
        Schema::table('teams', function (Blueprint $table) {
            $table->index(['is_active', 'order'], 'teams_active_order_idx');
            $table->index('name', 'teams_name_idx');
            $table->index('position', 'teams_position_idx');
            $table->index('email', 'teams_email_idx');
            $table->index('order', 'teams_order_idx');
        });

        // REFERENCES TABLE INDEXES
        Schema::table('references', function (Blueprint $table) {
            $table->index(['is_active', 'order'], 'references_active_order_idx');
            $table->index('client_name', 'references_client_name_idx');
            $table->index('company', 'references_company_idx');
            $table->index('order', 'references_order_idx');
        });

        // FAQS TABLE INDEXES
        Schema::table('faqs', function (Blueprint $table) {
            $table->index(['is_active', 'order'], 'faqs_active_order_idx');
            $table->index('question', 'faqs_question_idx');
            $table->index('order', 'faqs_order_idx');
        });

        // ABOUTS TABLE INDEXES
        Schema::table('abouts', function (Blueprint $table) {
            $table->index('title', 'abouts_title_idx');
            $table->index('created_at', 'abouts_created_at_idx');
        });

        // GALLERIES TABLE INDEXES
        Schema::table('galleries', function (Blueprint $table) {
            $table->index('name', 'galleries_name_idx');
            $table->index('created_at', 'galleries_created_at_idx');
        });

        // SITE_SETTINGS TABLE INDEXES
        Schema::table('site_settings', function (Blueprint $table) {
            $table->index('is_maintenance', 'site_settings_maintenance_idx');
            $table->index('site_name', 'site_settings_name_idx');
        });

        // MEDIA TABLE ADDITIONAL INDEXES (Spatie Media Library için)
        Schema::table('media', function (Blueprint $table) {
            $table->index('collection_name', 'media_collection_idx');
            $table->index('mime_type', 'media_mime_type_idx');
            $table->index(['model_type', 'model_id', 'collection_name'], 'media_model_collection_idx');
        });

        // CACHE TABLE OPTIMIZATION
        Schema::table('cache', function (Blueprint $table) {
            $table->index('expiration', 'cache_expiration_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // BLOGS TABLE INDEXES DROP
        Schema::table('blogs', function (Blueprint $table) {
            $table->dropIndex('blogs_active_published_idx');
            $table->dropIndex('blogs_active_order_idx');
            $table->dropIndex('blogs_active_views_idx');
            $table->dropIndex('blogs_category_active_idx');
            $table->dropIndex('blogs_published_at_idx');
            $table->dropIndex('blogs_views_idx');
            $table->dropIndex('blogs_order_idx');
            $table->dropIndex('blogs_author_idx');
            $table->dropIndex('blogs_created_at_idx');
        });

        // BLOG_CATEGORIES TABLE INDEXES DROP
        Schema::table('blog_categories', function (Blueprint $table) {
            $table->dropIndex('blog_categories_active_idx');
            $table->dropIndex('blog_categories_name_idx');
            $table->dropIndex('blog_categories_created_at_idx');
        });

        // PRODUCTS TABLE INDEXES DROP
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('products_active_price_idx');
            $table->dropIndex('products_category_active_idx');
            $table->dropIndex('products_name_idx');
            $table->dropIndex('products_price_idx');
            $table->dropIndex('products_created_at_idx');
        });

        // PRODUCT_CATEGORIES TABLE INDEXES DROP
        Schema::table('product_categories', function (Blueprint $table) {
            $table->dropIndex('product_categories_active_sort_idx');
            $table->dropIndex('product_categories_name_idx');
            $table->dropIndex('product_categories_sort_idx');
        });

        // TEAMS TABLE INDEXES DROP
        Schema::table('teams', function (Blueprint $table) {
            $table->dropIndex('teams_active_order_idx');
            $table->dropIndex('teams_name_idx');
            $table->dropIndex('teams_position_idx');
            $table->dropIndex('teams_email_idx');
            $table->dropIndex('teams_order_idx');
        });

        // REFERENCES TABLE INDEXES DROP
        Schema::table('references', function (Blueprint $table) {
            $table->dropIndex('references_active_order_idx');
            $table->dropIndex('references_client_name_idx');
            $table->dropIndex('references_company_idx');
            $table->dropIndex('references_order_idx');
        });

        // FAQS TABLE INDEXES DROP
        Schema::table('faqs', function (Blueprint $table) {
            $table->dropIndex('faqs_active_order_idx');
            $table->dropIndex('faqs_question_idx');
            $table->dropIndex('faqs_order_idx');
        });

        // ABOUTS TABLE INDEXES DROP
        Schema::table('abouts', function (Blueprint $table) {
            $table->dropIndex('abouts_title_idx');
            $table->dropIndex('abouts_created_at_idx');
        });

        // GALLERIES TABLE INDEXES DROP
        Schema::table('galleries', function (Blueprint $table) {
            $table->dropIndex('galleries_name_idx');
            $table->dropIndex('galleries_created_at_idx');
        });

        // SITE_SETTINGS TABLE INDEXES DROP
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropIndex('site_settings_maintenance_idx');
            $table->dropIndex('site_settings_name_idx');
        });

        // MEDIA TABLE INDEXES DROP
        Schema::table('media', function (Blueprint $table) {
            $table->dropIndex('media_collection_idx');
            $table->dropIndex('media_mime_type_idx');
            $table->dropIndex('media_model_collection_idx');
        });

        // CACHE TABLE INDEXES DROP
        Schema::table('cache', function (Blueprint $table) {
            $table->dropIndex('cache_expiration_idx');
        });
    }
};
