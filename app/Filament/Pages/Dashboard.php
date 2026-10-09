<?php

namespace App\Filament\Pages;

use Filament\Actions\Action;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $title = 'Panel de Control';

    /**
     * Acciones del encabezado del Dashboard con enlace directo al Frontend.
     */
    protected function getHeaderActions(): array
    {
        return [
            Action::make('view_frontend')
                ->label('Ver Frontend / Web')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->color('primary')
                ->url(url('/'))
                ->openUrlInNewTab(),
            Action::make('simulate_registration')
                ->label('Simular Registro Móvil')
                ->icon('heroicon-o-device-phone-mobile')
                ->color('gray')
                ->url(route('registration.form'))
                ->openUrlInNewTab(),
        ];
    }
}
