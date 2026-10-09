<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
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
            ->brandName('Visual Corporativo • SpotVision')
            ->favicon('/favicon.ico')
            ->colors([
                'primary' => Color::Emerald,
                'secondary' => Color::Indigo,
                'warning' => Color::Amber,
                'danger' => Color::Rose,
                'info' => Color::Cyan,
            ])
            ->navigationGroups([
                'Acceso Rápido',
                'Fase 1: Atracción y Registro',
                'Fase 2: Tamizaje SpotVision',
                'Fase 3: Envíos WhatsApp y Reportes',
                'Fase 4: Cierre y CRM en Sitio',
                'Fase 5: Retargeting y Automatización',
            ])
            ->navigationItems([
                \Filament\Navigation\NavigationItem::make('Ver Frontend (Web)')
                    ->url('/')
                    ->icon('heroicon-o-globe-alt')
                    ->group('Acceso Rápido')
                    ->sort(-10)
                    ->openUrlInNewTab(),
            ])
            ->maxContentWidth('full')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                \App\Filament\Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                \App\Filament\Widgets\AiStatusWidget::class,
                Widgets\AccountWidget::class,
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
