<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class WelcomeWidget extends Widget
{
    protected static string $view = 'filament.widgets.welcome';

    protected static ?int $sort = -1; // En üstte görünsün

    protected int|string|array $columnSpan = 'full';

    protected function getViewData(): array
    {
        $currentHour = now()->format('H');
        $greeting = match (true) {
            $currentHour >= 5 && $currentHour < 12 => 'Günaydın',
            $currentHour >= 12 && $currentHour < 17 => 'İyi öğleden sonralar',
            $currentHour >= 17 && $currentHour < 22 => 'İyi akşamlar',
            default => 'İyi geceler'
        };

        return [
            'greeting' => $greeting,
            'current_date' => now()->format('d F Y, l'),
            'user_name' => auth()->user()->name ?? 'Kullanıcı',
            'weather_emoji' => $this->getWeatherEmoji(),
        ];
    }

    private function getWeatherEmoji(): string
    {
        $emojis = ['☀️', '⛅', '🌤️', '🌦️', '🌧️'];

        return $emojis[array_rand($emojis)];
    }
}
