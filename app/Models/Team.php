<?php

namespace App\Models;

use App\Services\CacheService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    /** @use HasFactory<\Database\Factories\TeamFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'position',
        'photo',
        'biography',
        'email',
        'phone',
        'linkedin',
        'is_active',
        'order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Scope: Aktif ekip üyeleri
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Sıraya göre
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('order', 'asc')->orderBy('name', 'asc');
    }

    /**
     * Cache'li aktif ekip üyelerini getir
     */
    public static function getCachedActiveTeamMembers()
    {
        $key = CacheService::generateKey('active_team_members');
        
        return CacheService::remember(
            $key,
            CacheService::LONG_TTL,
            function () {
                return static::active()
                    ->ordered()
                    ->get();
            }
        );
    }

    /**
     * Cache'li pozisyona göre ekip üyelerini getir
     */
    public static function getCachedTeamMembersByPosition(string $position)
    {
        $key = CacheService::generateKey('team_members_by_position', $position);
        
        return CacheService::remember(
            $key,
            CacheService::DEFAULT_TTL,
            function () use ($position) {
                return static::active()
                    ->where('position', 'like', '%' . $position . '%')
                    ->ordered()
                    ->get();
            }
        );
    }

    /**
     * Cache'li ekip üyesi detayını getir
     */
    public static function getCachedTeamMember(int $id)
    {
        $key = CacheService::generateKey('team_member', $id);
        
        return CacheService::remember(
            $key,
            CacheService::LONG_TTL,
            function () use ($id) {
                return static::active()->find($id);
            }
        );
    }
}
