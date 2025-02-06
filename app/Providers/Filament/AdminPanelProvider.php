<?php

namespace App\Providers\Filament;

use App\Filament\Widgets\GraficoStock;
use App\Models\Empresa;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
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
use Illuminate\Support\Facades\Storage;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        try {
            $empresa = Empresa::first();
            $logo = $empresa ? Storage::url($empresa->logo) : null;
        } catch (\Exception $e) {
            $logo = null; // O una imagen por defecto
        }

        //$empresa = Empresa::first();
        return $panel
            ->font('Roboto Flex')
            ->default()
            ->id('admin')
            ->path('/')
            ->login()
            ->colors([
                'primary' => '#D4AF37',    // Dorado
                'secondary' => '#2d3648',  // Azul oscuro
                'success' => '#10B981',    // Verde compatible
                'danger' => '#ef4444',     // Rojo
                'warning' => '#f59e0b',    // Naranja
                'info' => '#3b82f6',       // Azul
                'gray' => '#6b7280',       // Gris
                'dark' => '#1f2937'        // Negro
            ])
            ->brandLogo($logo)
            //->brandLogo(fn() => $empresa ? Storage::url($empresa->logo) : null)
            ->profile()
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            //->brandLogo(asset('images/logo.jpg')) // Icono en la esquina superior izquierda
            ->brandLogoHeight('4rem')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
                GraficoStock::class,
            ])

            ->sidebarCollapsibleOnDesktop()

            ->sidebarWidth('15rem')
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
