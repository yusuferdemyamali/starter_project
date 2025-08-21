<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Cache'li ürün listesi
     */
    public function index(Request $request): JsonResponse
    {
        $limit = min($request->get('limit', 20), 100); // Max 100

        $products = Product::getCachedActiveProducts($limit);

        return response()->json([
            'success' => true,
            'data' => $products,
            'meta' => [
                'count' => $products->count(),
                'cache_enabled' => true,
            ],
        ]);
    }

    /**
     * Kategoriye göre ürünler - cache'li
     */
    public function byCategory(int $categoryId, Request $request): JsonResponse
    {
        $limit = min($request->get('limit', 20), 100);

        $products = Product::getCachedProductsByCategory($categoryId, $limit);

        return response()->json([
            'success' => true,
            'data' => $products,
            'meta' => [
                'count' => $products->count(),
                'category_id' => $categoryId,
                'cache_enabled' => true,
            ],
        ]);
    }

    /**
     * Fiyat aralığına göre ürünler - cache'li
     */
    public function byPriceRange(Request $request): JsonResponse
    {
        $request->validate([
            'min_price' => 'required|numeric|min:0',
            'max_price' => 'required|numeric|gt:min_price',
            'limit' => 'integer|min:1|max:100',
        ]);

        $minPrice = $request->get('min_price');
        $maxPrice = $request->get('max_price');
        $limit = $request->get('limit', 20);

        $products = Product::getCachedProductsByPriceRange($minPrice, $maxPrice, $limit);

        return response()->json([
            'success' => true,
            'data' => $products,
            'meta' => [
                'count' => $products->count(),
                'price_range' => [
                    'min' => $minPrice,
                    'max' => $maxPrice,
                ],
                'cache_enabled' => true,
            ],
        ]);
    }

    /**
     * Öne çıkan ürünler - cache'li
     */
    public function featured(Request $request): JsonResponse
    {
        $limit = min($request->get('limit', 8), 20);

        $products = Product::getCachedFeaturedProducts($limit);

        return response()->json([
            'success' => true,
            'data' => $products,
            'meta' => [
                'count' => $products->count(),
                'cache_enabled' => true,
            ],
        ]);
    }

    /**
     * Tekil ürün detayı
     */
    public function show(int $id): JsonResponse
    {
        $product = Product::with('category')->find($id);

        if (! $product || ! $product->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Ürün bulunamadı',
            ], 404);
        }

        $relatedProducts = $product->getRelatedProducts(4);

        return response()->json([
            'success' => true,
            'data' => [
                'product' => $product,
                'related_products' => $relatedProducts,
            ],
            'meta' => [
                'cache_enabled' => true,
            ],
        ]);
    }

    /**
     * Kategoriler - cache'li
     */
    public function categories(): JsonResponse
    {
        $categories = ProductCategory::getCachedActiveCategories();

        return response()->json([
            'success' => true,
            'data' => $categories,
            'meta' => [
                'count' => $categories->count(),
                'cache_enabled' => true,
            ],
        ]);
    }

    /**
     * Popüler kategoriler - cache'li
     */
    public function popularCategories(Request $request): JsonResponse
    {
        $limit = min($request->get('limit', 6), 20);

        $categories = ProductCategory::getCachedPopularCategories($limit);

        return response()->json([
            'success' => true,
            'data' => $categories,
            'meta' => [
                'count' => $categories->count(),
                'cache_enabled' => true,
            ],
        ]);
    }
}
