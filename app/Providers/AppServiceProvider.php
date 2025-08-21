<?php

namespace App\Providers;

use App\Models\About;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\Faq;
use App\Models\Gallery;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Reference;
use App\Models\SiteSetting;
use App\Models\Team;
use App\Observers\AboutObserver;
use App\Observers\BlogCategoryObserver;
use App\Observers\BlogObserver;
use App\Observers\FaqObserver;
use App\Observers\GalleryObserver;
use App\Observers\ProductCategoryObserver;
use App\Observers\ProductObserver;
use App\Observers\ReferenceObserver;
use App\Observers\SiteSettingObserver;
use App\Observers\TeamObserver;
use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;
use Filament\Support\Facades\FilamentView;
use Illuminate\Contracts\View\View;
use Illuminate\Support\ServiceProvider;

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
        // Model Observer'larını kaydet - Cache invalidation için
        Blog::observe(BlogObserver::class);
        BlogCategory::observe(BlogCategoryObserver::class);
        Product::observe(ProductObserver::class);
        ProductCategory::observe(ProductCategoryObserver::class);
        
        // Diğer modüller için observer'lar
        Team::observe(TeamObserver::class);
        Gallery::observe(GalleryObserver::class);
        Reference::observe(ReferenceObserver::class);
        Faq::observe(FaqObserver::class);
        About::observe(AboutObserver::class);
        SiteSetting::observe(SiteSettingObserver::class);

        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            $switch
                ->locales(['tr', 'en']); // also accepts a closure
        });

        FilamentView::registerRenderHook(
            'panels::auth.login.form.after',
            fn (): View => view('filament.login_extra')
        );
    }
}
