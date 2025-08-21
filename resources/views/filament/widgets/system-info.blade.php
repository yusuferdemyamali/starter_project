<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center gap-2">
                <svg class="h-5 w-5 text-info-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Sistem Bilgisi
            </div>
        </x-slot>

        <div class="space-y-6">
            <!-- Sistem Detayları -->
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-300">Laravel</span>
                        <span class="text-sm font-mono text-primary-600 dark:text-primary-400">{{ $system_info['laravel_version'] }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-300">PHP</span>
                        <span class="text-sm font-mono text-success-600 dark:text-success-400">{{ $system_info['php_version'] }}</span>
                    </div>
                </div>
                <div class="space-y-2">
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-300">Ortam</span>
                        <span class="text-xs px-2 py-1 rounded-full {{ $system_info['app_env'] === 'production' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' }}">
                            {{ strtoupper($system_info['app_env']) }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-300">Veritabanı</span>
                        <span class="text-sm font-mono text-info-600 dark:text-info-400">{{ $system_info['database_name'] }}</span>
                    </div>
                </div>
            </div>

            <!-- Aktivite Bilgileri -->
            <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Son Aktivite
                </h4>
                
                <div class="grid grid-cols-2 gap-3">
                    <div class="text-center p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                        <div class="text-lg font-bold text-blue-600 dark:text-blue-400">{{ $recent_activity['total_users'] }}</div>
                        <div class="text-xs text-blue-500 dark:text-blue-300">Toplam Kullanıcı</div>
                    </div>
                    <div class="text-center p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                        <div class="text-lg font-bold text-green-600 dark:text-green-400">{{ $recent_activity['content_today'] }}</div>
                        <div class="text-xs text-green-500 dark:text-green-300">Bugünkü İçerik</div>
                    </div>
                    <div class="text-center p-3 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                        <div class="text-lg font-bold text-purple-600 dark:text-purple-400">{{ $recent_activity['storage_usage'] }}</div>
                        <div class="text-xs text-purple-500 dark:text-purple-300">Depolama Kullanımı</div>
                    </div>
                    <div class="text-center p-3 bg-orange-50 dark:bg-orange-900/20 rounded-lg">
                        <div class="text-xs font-bold text-orange-600 dark:text-orange-400">Aktif</div>
                        <div class="text-xs text-orange-500 dark:text-orange-300">Sistem Durumu</div>
                    </div>
                </div>
            </div>

            <!-- Son Yedekleme Bilgisi -->
            <div class="border-t border-gray-200 dark:border-gray-700 pt-3">
                <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                    <div class="flex items-center gap-2">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        <span>Son Yedekleme:</span>
                    </div>
                    <span class="font-mono">{{ $system_info['last_backup'] }}</span>
                </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
