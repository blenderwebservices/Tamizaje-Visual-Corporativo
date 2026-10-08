<?php

namespace App\Filament\Pages;

use App\Models\Client;
use App\Models\RetargetingLog;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class RetargetingCampaignsPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-arrow-path-rounded-square';

    protected static ?string $navigationGroup = 'Fase 5: Retargeting y Automatización';

    protected static ?string $title = 'Retargeting y Automatización';

    protected static ?string $navigationLabel = 'Seguimiento (Día 3, 15, 90)';

    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.pages.retargeting-campaigns-page';

    public string $activeTab = 'day_3'; // day_3, day_15, day_90

    public function markAsSent(int $logId): void
    {
        $log = RetargetingLog::find($logId);
        if ($log) {
            $log->update([
                'status' => 'sent',
                'sent_at' => now(),
            ]);

            Notification::make()
                ->title('Mensaje de Retargeting Registrado')
                ->body("Seguimiento enviado a {$log->client?->full_name}.")
                ->success()
                ->send();
        }
    }

    public function getCampaignData(): array
    {
        $logs = RetargetingLog::with(['client.latestScreening', 'client.company'])
            ->where('stage', $this->activeTab)
            ->whereHas('client', function ($q) {
                // Excluir a quienes ya compraron en sitio
                $q->where('crm_stage', '!=', 'purchased_onsite');
            })
            ->latest('scheduled_for')
            ->paginate(15);

        $counts = [
            'day_3' => RetargetingLog::where('stage', 'day_3')->where('status', 'pending')->count(),
            'day_15' => RetargetingLog::where('stage', 'day_15')->where('status', 'pending')->count(),
            'day_90' => RetargetingLog::where('stage', 'day_90')->where('status', 'pending')->count(),
        ];

        return [
            'logs' => $logs,
            'counts' => $counts,
        ];
    }

    public function getCopyForStage(string $stage, Client $client): string
    {
        $name = $client->first_name ?: $client->full_name;
        $screening = $client->latestScreening;
        $findings = $screening && !empty($screening->findings) ? implode(', ', $screening->findings) : 'oportunidades de descanso visual';

        return match ($stage) {
            'day_3' => "Hola {$name}! 👋 Hace unos días realizamos tu tamizaje visual con SpotVision. Cuidar tus ojos de la fatiga digital y riesgos laborales es vital. Te compartimos nuestro catálogo digital de lentes de seguridad industrial y armazones con filtro Blue Defense: https://visualcorporativo.com/catalogo\n¿Te gustaría probarte algún modelo?",
            'day_15' => "Hola {$name}! 🎁 En base a tu detección de {$findings} en tu tamizaje SpotVision, te activamos un *Cupón Exclusivo del 20% de Descuento* en tus lentes graduados o de seguridad con opción a descuento vía nómina en tu empresa. Válido por 5 días. ¡Pregúntanos por los armazones disponibles!",
            'day_90' => "Hola {$name}! 🗓️ Han pasado 90 días desde tu tamizaje SpotVision. Te invitamos a agendar tu examen visual completo clínico para actualización de graduación o chequeo de salud ocular. ¿Te agendamos un espacio esta semana?",
            default => "Hola {$name}, te saludamos de Visual Corporativo.",
        };
    }
}
