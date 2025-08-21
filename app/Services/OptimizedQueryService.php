<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * OptimizedQueryService - Database query optimizasyon servisi
 * 
 * Bu servis, tüm modeller için optimize edilmiş query metodları sağlar.
 * Index'leri kullanarak performansı maksimize eder.
 */
class OptimizedQueryService
{
    /**
     * Aktif kayıtları optimize edilmiş şekilde getirir
     */
    public function getActiveRecords(string $modelClass, int $limit = null): Collection
    {
        $query = $modelClass::where('is_active', true);
        
        // Order field'ı varsa sırala
        if ($this->hasOrderField($modelClass)) {
            $query->orderBy('order', 'asc');
        }
        
        // Created_at ile fallback sıralama
        $query->orderBy('created_at', 'desc');
        
        if ($limit) {
            $query->limit($limit);
        }
        
        return $query->get();
    }
    
    /**
     * Kategoriye göre optimize edilmiş kayıtları getirir
     */
    public function getRecordsByCategory(string $modelClass, int $categoryId, int $limit = null): Collection
    {
        $query = $modelClass::where('is_active', true)
            ->where('category_id', $categoryId);
        
        if ($this->hasOrderField($modelClass)) {
            $query->orderBy('order', 'asc');
        }
        
        $query->orderBy('created_at', 'desc');
        
        if ($limit) {
            $query->limit($limit);
        }
        
        return $query->get();
    }
    
    /**
     * Popüler kayıtları getirir (views bazlı)
     */
    public function getPopularRecords(string $modelClass, int $limit = 10): Collection
    {
        return $modelClass::where('is_active', true)
            ->orderBy('views', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
    
    /**
     * Son yayınlanan kayıtları getirir
     */
    public function getRecentPublished(string $modelClass, int $limit = 10): Collection
    {
        $query = $modelClass::where('is_active', true);
        
        // Published_at field'ı varsa kullan
        if ($this->hasPublishedAtField($modelClass)) {
            $query->where('published_at', '<=', now())
                ->orderBy('published_at', 'desc');
        } else {
            $query->orderBy('created_at', 'desc');
        }
        
        return $query->limit($limit)->get();
    }
    
    /**
     * Arama optimizasyonu - index'li fieldlarda arar
     */
    public function searchOptimized(string $modelClass, string $term, int $limit = 20): Collection
    {
        $model = new $modelClass;
        $searchFields = $this->getSearchableFields($modelClass);
        
        $query = $modelClass::where('is_active', true);
        
        // İlk field için where, diğerleri için orWhere
        $firstField = array_shift($searchFields);
        if ($firstField) {
            $query->where($firstField, 'like', "%{$term}%");
            
            foreach ($searchFields as $field) {
                $query->orWhere($field, 'like', "%{$term}%");
            }
        }
        
        if ($this->hasOrderField($modelClass)) {
            $query->orderBy('order', 'asc');
        }
        
        $query->orderBy('created_at', 'desc');
        
        return $query->limit($limit)->get();
    }
    
    /**
     * Batch işlemler için optimize edilmiş query
     */
    public function getBatchRecords(string $modelClass, array $ids): Collection
    {
        return $modelClass::whereIn('id', $ids)
            ->where('is_active', true)
            ->get();
    }
    
    /**
     * Model'in order field'ına sahip olup olmadığını kontrol eder
     */
    private function hasOrderField(string $modelClass): bool
    {
        $model = new $modelClass;
        return in_array('order', $model->getFillable());
    }
    
    /**
     * Model'in published_at field'ına sahip olup olmadığını kontrol eder
     */
    private function hasPublishedAtField(string $modelClass): bool
    {
        $model = new $modelClass;
        return in_array('published_at', $model->getFillable());
    }
    
    /**
     * Model için aranabilir field'ları döner
     */
    private function getSearchableFields(string $modelClass): array
    {
        $searchableFields = [
            'App\Models\Blog' => ['title', 'content', 'author'],
            'App\Models\Product' => ['name', 'description'],
            'App\Models\Team' => ['name', 'position', 'biography'],
            'App\Models\Reference' => ['client_name', 'company', 'testimonial'],
            'App\Models\Faq' => ['question', 'answer'],
            'App\Models\About' => ['title', 'content'],
            'App\Models\Gallery' => ['name', 'description'],
        ];
        
        return $searchableFields[$modelClass] ?? ['name'];
    }
}
