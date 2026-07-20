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

        // Dynamically share categories and SEO with all views
        \Illuminate\Support\Facades\View::composer('*', function ($view) {
            if (\Illuminate\Support\Facades\Schema::hasTable('categories')) {
                $view->with('footerCategories', \App\Models\Category::whereNull('parent_id')->with('children')->orderBy('name')->get());
            } else {
                $view->with('footerCategories', collect());
            }

            // Share page SEO data based on current route
            if (\Illuminate\Support\Facades\Schema::hasTable('page_seos')) {
                $pageId = null;
                $request = request();

                if ($request->routeIs('products.index') && !$request->get('search') && !$request->get('category') && !$request->get('brand')) {
                    $pageId = 'home';
                } elseif ($request->routeIs('about')) {
                    $pageId = 'about';
                } elseif ($request->routeIs('contact')) {
                    $pageId = 'contact';
                }

                if ($pageId) {
                    $view->with('pageSeo', \App\Models\PageSeo::forPage($pageId));
                } else {
                    $view->with('pageSeo', null);
                }
            } else {
                $view->with('pageSeo', null);
            }
        });
    }
}
