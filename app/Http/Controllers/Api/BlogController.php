<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Cache'li blog listesi
     */
    public function index(Request $request): JsonResponse
    {
        $limit = min($request->get('limit', 10), 50); // Max 50
        
        $blogs = Blog::getCachedActiveBlogs($limit);
        
        return response()->json([
            'success' => true,
            'data' => $blogs,
            'meta' => [
                'count' => $blogs->count(),
                'cache_enabled' => true
            ]
        ]);
    }

    /**
     * Kategori blogları - cache'li
     */
    public function byCategory(int $categoryId, Request $request): JsonResponse
    {
        $limit = min($request->get('limit', 10), 50);
        
        $blogs = Blog::getCachedBlogsByCategory($categoryId, $limit);
        
        return response()->json([
            'success' => true,
            'data' => $blogs,
            'meta' => [
                'count' => $blogs->count(),
                'category_id' => $categoryId,
                'cache_enabled' => true
            ]
        ]);
    }

    /**
     * Tekil blog - cache'li
     */
    public function show(string $slug): JsonResponse
    {
        $blog = Blog::getCachedBlogBySlug($slug);
        
        if (!$blog) {
            return response()->json([
                'success' => false,
                'message' => 'Blog bulunamadı'
            ], 404);
        }
        
        $relatedBlogs = $blog->getCachedRelatedBlogs(5);
        
        return response()->json([
            'success' => true,
            'data' => [
                'blog' => $blog,
                'related_blogs' => $relatedBlogs
            ],
            'meta' => [
                'cache_enabled' => true
            ]
        ]);
    }

    /**
     * Kategoriler - cache'li
     */
    public function categories(): JsonResponse
    {
        $categories = BlogCategory::getCachedActiveCategories();
        
        return response()->json([
            'success' => true,
            'data' => $categories,
            'meta' => [
                'count' => $categories->count(),
                'cache_enabled' => true
            ]
        ]);
    }
}
