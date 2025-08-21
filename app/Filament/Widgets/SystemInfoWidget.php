<?php

namespace App\Filament\Widgets;

use App\Models\Blog;
use App\Models\Product;
use App\Models\Reference;
use App\Models\User;
use Filament\Widgets\Widget;
use Illuminate\Support\Carbon;

class SystemInfoWidget extends Widget
{
    protected static string $view = 'filament.widgets.system-info';

    protected static ?int $sort = 5;

    protected int|string|array $columnSpan = 1;

    protected function getViewData(): array
    {
        return [
            'system_info' => [
                'laravel_version' => app()->version(),
                'php_version' => phpversion(),
                'database_name' => config('database.connections.mysql.database'),
                'app_env' => config('app.env'),
                'last_backup' => 'Henüz yapılmadı', // Bu gerçek bir backup sistemi ile entegre edilebilir
            ],
            'recent_activity' => [
                'total_users' => User::count(),
                'content_today' => $this->getContentAddedToday(),
                'server_uptime' => $this->getServerUptime(),
                'storage_usage' => $this->getStorageUsage(),
            ],
        ];
    }

    private function getContentAddedToday(): int
    {
        $today = Carbon::today();

        return Blog::whereDate('created_at', $today)->count() +
               Product::whereDate('created_at', $today)->count() +
               Reference::whereDate('created_at', $today)->count();
    }

    private function getServerUptime(): string
    {
        // Bu basit bir örnek, gerçek server uptime için sistem komutları kullanılabilir
        $uptime = now()->diffForHumans(now()->subDays(rand(1, 30)));

        return $uptime;
    }

    private function getStorageUsage(): string
    {
        $storagePath = storage_path();
        if (function_exists('disk_free_space')) {
            $freeSpace = disk_free_space($storagePath);
            $totalSpace = disk_total_space($storagePath);
            $usedSpace = $totalSpace - $freeSpace;
            $usagePercent = round(($usedSpace / $totalSpace) * 100, 2);

            return $usagePercent.'%';
        }

        return 'Bilinmiyor';
    }
}
