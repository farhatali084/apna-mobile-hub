<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Schema;

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
        Schema::defaultStringLength(191);

        \Illuminate\Pagination\Paginator::useBootstrapFive();

        \Filament\Support\Facades\FilamentAsset::register([
            \Filament\Support\Assets\Css::make('sedap-theme', asset('css/sedap-theme.css')),
        ]);

        // Dynamically share categories with all views for footer links
        \Illuminate\Support\Facades\View::composer('*', function ($view) {
            if (\Illuminate\Support\Facades\Schema::hasTable('categories')) {
                $view->with('footerCategories', \App\Models\Category::orderBy('name')->get());
            } else {
                $view->with('footerCategories', collect());
            }
        });
    }
}
