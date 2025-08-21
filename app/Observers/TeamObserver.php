<?php

namespace App\Observers;

use App\Models\Team;
use App\Services\CacheService;

class TeamObserver
{
    /**
     * Handle the Team "created" event.
     */
    public function created(Team $team): void
    {
        $this->clearCache();
    }

    /**
     * Handle the Team "updated" event.
     */
    public function updated(Team $team): void
    {
        $this->clearCache();
    }

    /**
     * Handle the Team "deleted" event.
     */
    public function deleted(Team $team): void
    {
        $this->clearCache();
    }

    /**
     * Handle the Team "restored" event.
     */
    public function restored(Team $team): void
    {
        $this->clearCache();
    }

    /**
     * Handle the Team "force deleted" event.
     */
    public function forceDeleted(Team $team): void
    {
        $this->clearCache();
    }

    /**
     * Cache'leri temizle
     */
    private function clearCache(): void
    {
        $patterns = [
            'active_team_members',
            'team_members_by_position',
            'team_member'
        ];

        foreach ($patterns as $pattern) {
            for ($i = 1; $i <= 50; $i++) {
                CacheService::remember($pattern . '_' . $i, 1, fn() => null);
            }
        }
    }
}
