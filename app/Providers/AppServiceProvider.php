<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\View as ViewFacade;
use Filament\Support\Facades\FilamentView;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            $switch
                ->locales(['tr', 'en']); // also accepts a closure
        });

        FilamentView::registerRenderHook(
            'panels::auth.login.form.after',
            fn(): View => view('filament.login_extra')
        );
    }
}
