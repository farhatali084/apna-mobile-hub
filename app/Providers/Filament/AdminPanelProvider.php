<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use App\Filament\Pages\Dashboard;
use Filament\Navigation\NavigationItem;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::hex('#2563EB'),
            ])
            ->brandName('Sedap.')
            ->brandLogo(fn () => view('filament.logo'))
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                // Empty to allow custom dashboard page layout
            ])
            ->renderHook('panels::sidebar.navigation.end', fn () => view('filament.sidebar-footer'))
            ->renderHook('panels::topbar.end', fn () => view('filament.topbar-end'))
            ->navigationItems([
                NavigationItem::make('Dashboard')
                    ->url(fn () => \App\Filament\Pages\Dashboard::getUrl())
                    ->icon('heroicon-o-home')
                    ->isActiveWhen(fn () => request()->routeIs('filament.admin.pages.dashboard')),
                NavigationItem::make('Order List')
                    ->url(fn () => \App\Filament\Resources\OrderResource::getUrl())
                    ->icon('heroicon-o-list-bullet')
                    ->isActiveWhen(fn () => request()->routeIs('filament.admin.resources.orders.index')),
                /*
                NavigationItem::make('Order Detail')
                    ->url('#')
                    ->icon('heroicon-o-document-text')
                    ->isActiveWhen(fn () => request()->routeIs('filament.admin.resources.orders.edit')),
                */
                /*
                NavigationItem::make('Customer')
                    ->url('#')
                    ->icon('heroicon-o-users'),
                
                NavigationItem::make('Analytics')
                    ->url('#')
                    ->icon('heroicon-o-chart-bar'),
                    */
                NavigationItem::make('Reviews')
                    ->url(fn () => \App\Filament\Resources\ReviewResource::getUrl())
                    ->icon('heroicon-o-pencil-square')
                    ->isActiveWhen(fn () => request()->routeIs('filament.admin.resources.reviews.*')),
                NavigationItem::make('Products')
                    ->url(fn () => \App\Filament\Resources\Products\ProductResource::getUrl())
                    ->icon('heroicon-o-shopping-bag')
                    ->isActiveWhen(fn () => request()->routeIs('filament.admin.resources.products.*')),
                /*
                NavigationItem::make('Product Detail')
                    ->url('#')
                    ->icon('heroicon-o-clipboard-document-list'),
                NavigationItem::make('Customer Detail')
                    ->url('#')
                    ->icon('heroicon-o-user-circle'),
                NavigationItem::make('Calendar')
                    ->url('#')
                    ->icon('heroicon-o-calendar'),
                NavigationItem::make('Chat')
                    ->url('#')
                    ->icon('heroicon-o-chat-bubble-left-right'),
                NavigationItem::make('Wallet')
                    ->url('#')
                    ->icon('heroicon-o-credit-card'),
                */
                NavigationItem::make('Settings')
                    ->url(fn () => \App\Filament\Resources\SettingResource::getUrl())
                    ->icon('heroicon-o-cog-6-tooth')
                    ->isActiveWhen(fn () => request()->routeIs('filament.admin.resources.settings.*')),
                NavigationItem::make('Categories')
                    ->url(fn () => \App\Filament\Resources\CategoryResource::getUrl())
                    ->icon('heroicon-o-tag')
                    ->isActiveWhen(fn () => request()->routeIs('filament.admin.resources.categories.*')),
                NavigationItem::make('Filter Groups')
                    ->url(fn () => \App\Filament\Resources\FilterGroupResource::getUrl())
                    ->icon('heroicon-o-funnel')
                    ->isActiveWhen(fn () => request()->routeIs('filament.admin.resources.filter-groups.*')),
                NavigationItem::make('SEO Manager')
                    ->url(fn () => \App\Filament\Resources\PageSeoResource::getUrl())
                    ->icon('heroicon-o-magnifying-glass')
                    ->isActiveWhen(fn () => request()->routeIs('filament.admin.resources.page-seos.*')),
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
