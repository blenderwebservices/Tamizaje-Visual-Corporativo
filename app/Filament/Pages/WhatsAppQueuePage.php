<?php

namespace App\Filament\Pages;

use App\Models\Company;
use App\Models\Screening;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class WhatsAppQueuePage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?string $navigationGroup = 'Fase 3: Envíos WhatsApp y Reportes';

    protected static ?string $title = 'Cola de Envíos WhatsApp';

    protected static ?string $navigationLabel = 'Cola de WhatsApp';

    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.pages.whats-app-queue-page';

    public ?string $filterStatus = 'pending';
    public ?int $filterCompany = null;
    public ?string $search = '';

    public function sendViaCloudApi(int $id): void
    {
        $screening = Screening::find($id);
        if (! $screening) {
            return;
        }

        if (! \App\Services\WhatsAppCloudApiService::isConfigured()) {
            Notification::make()
                ->title('API Oficial de Enjoy Vision no configurada')
                ->body('Debes agregar WHATSAPP_PHONE_ID y WHATSAPP_ACCESS_TOKEN en tu archivo .env. Puedes usar el botón "WhatsApp Web (Operador)" mientras tanto.')
                ->warning()
                ->send();
            return;
        }

        $service = app(\App\Services\WhatsAppCloudApiService::class);
        $result = $service->sendScreeningReport($screening);

        if ($result['success']) {
            Notification::make()
                ->title('Enviado desde Enjoy Vision')
                ->body($result['message'])
                ->success()
                ->send();
        } else {
            Notification::make()
                ->title('Error de envío API')
                ->body($result['message'])
                ->danger()
                ->send();
        }
    }

    public function markAsSent(int $id): void
    {
        $screening = Screening::find($id);
        if ($screening) {
            $screening->update([
                'whatsapp_status' => 'sent',
                'whatsapp_sent_at' => now(),
            ]);

            Notification::make()
                ->title('Marcado como Enviado')
                ->body("El reporte de {$screening->client?->full_name} quedó registrado como enviado.")
                ->success()
                ->send();
        }
    }

    public function markAsPending(int $id): void
    {
        $screening = Screening::find($id);
        if ($screening) {
            $screening->update([
                'whatsapp_status' => 'pending',
                'whatsapp_sent_at' => null,
            ]);

            Notification::make()
                ->title('Revertido a Pendiente')
                ->info()
                ->send();
        }
    }

    public function markAllVisibleAsSent(): void
    {
        $query = Screening::query();
        if ($this->filterStatus) {
            $query->where('whatsapp_status', $this->filterStatus);
        }
        if ($this->filterCompany) {
            $query->where('company_id', $this->filterCompany);
        }

        $count = $query->update([
            'whatsapp_status' => 'sent',
            'whatsapp_sent_at' => now(),
        ]);

        Notification::make()
            ->title("Se actualizaron {$count} registros como enviados.")
            ->success()
            ->send();
    }

    public function getViewData(): array
    {
        $query = Screening::with(['client', 'company'])->latest('exam_date');

        if ($this->filterStatus) {
            $query->where('whatsapp_status', $this->filterStatus);
        }

        if ($this->filterCompany) {
            $query->where('company_id', $this->filterCompany);
        }

        if (!empty($this->search)) {
            $search = '%' . trim($this->search) . '%';
            $query->whereHas('client', function ($q) use ($search) {
                $q->where('full_name', 'like', $search)
                  ->orWhere('phone', 'like', $search)
                  ->orWhere('subject_code', 'like', $search);
            });
        }

        $screenings = $query->paginate(15);

        $totalScreenings = Screening::count();
        $pendingCount = Screening::where('whatsapp_status', 'pending')->count();
        $sentCount = Screening::where('whatsapp_status', 'sent')->count();
        $companies = Company::pluck('name', 'id');

        return [
            'screenings' => $screenings,
            'totalScreenings' => $totalScreenings,
            'pendingCount' => $pendingCount,
            'sentCount' => $sentCount,
            'companies' => $companies,
            'isApiConfigured' => \App\Services\WhatsAppCloudApiService::isConfigured(),
        ];
    }
}
