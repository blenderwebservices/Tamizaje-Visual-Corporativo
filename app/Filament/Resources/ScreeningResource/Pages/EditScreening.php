<?php

namespace App\Filament\Resources\ScreeningResource\Pages;

use App\Filament\Resources\ScreeningResource;
use App\Models\Screening;
use App\Services\WhatsAppCloudApiService;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Mail;
use Throwable;

class EditScreening extends EditRecord
{
    protected static string $resource = ScreeningResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('viewGraduation')
                ->label('Ver Graduación')
                ->icon('heroicon-o-eye')
                ->color('indigo')
                ->url(fn (): string => $this->record->graduation_report_url, shouldOpenInNewTab: true),

            Actions\Action::make('sendGraduationWhatsApp')
                ->label('WhatsApp Graduación')
                ->icon('heroicon-o-chat-bubble-bottom-center-text')
                ->color('success')
                ->visible(fn (): bool => ! empty($this->record->client?->phone))
                ->action(function () {
                    /** @var Screening $record */
                    $record = $this->record;

                    if (WhatsAppCloudApiService::isConfigured()) {
                        $service = app(WhatsAppCloudApiService::class);
                        $result = $service->sendGraduationReport($record);
                        if ($result['success']) {
                            Notification::make()
                                ->title('Graduación Enviada por Enjoy Vision')
                                ->body($result['message'])
                                ->success()
                                ->send();
                            return;
                        }
                    }

                    // Respaldo WhatsApp Web del Operador
                    $record->update(['graduation_sent_at' => now()]);
                    Notification::make()
                        ->title('Abriendo WhatsApp Web')
                        ->body('Se abrió el reporte de graduación para envío manual.')
                        ->info()
                        ->send();

                    return redirect()->away($record->graduation_whatsapp_url);
                }),

            Actions\Action::make('sendGraduationEmail')
                ->label('Correo Graduación')
                ->icon('heroicon-o-envelope')
                ->color('warning')
                ->visible(fn (): bool => ! empty($this->record->client?->email))
                ->form([
                    Forms\Components\TextInput::make('email')
                        ->label('Correo del Paciente')
                        ->default(fn (): ?string => $this->record->client?->email)
                        ->email()
                        ->required(),
                ])
                ->action(function (array $data) {
                    /** @var Screening $record */
                    $record = $this->record;
                    $targetEmail = $data['email'];
                    $name = $record->client?->full_name ?? 'Paciente';
                    $gradUrl = $record->graduation_report_url;

                    try {
                        Mail::html("
                            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 24px; border: 1px solid #e2e8f0; border-radius: 16px; background-color: #ffffff;'>
                                <div style='text-align: center; margin-bottom: 20px;'>
                                    <h2 style='color: #4338ca; margin: 0;'>Enjoy Vision</h2>
                                    <p style='color: #64748b; font-size: 13px; margin: 4px 0 0 0;'>Salud Visual Corporativa • Reporte Oficial de Graduación</p>
                                </div>
                                <p style='color: #1e293b; font-size: 15px;'>Hola <strong>{$name}</strong>,</p>
                                <p style='color: #475569; font-size: 14px;'>Te compartimos tu fórmula optométrica y prescripción clínica confirmada en tu jornada visual.</p>
                                <div style='text-align: center; margin: 25px 0;'>
                                    <a href='{$gradUrl}' style='background-color: #4f46e5; color: #ffffff; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: bold; font-size: 14px; display: inline-block;'>
                                        Ver tu Receta y Graduación Digital
                                    </a>
                                </div>
                                <p style='color: #94a3b8; font-size: 12px; margin-top: 30px; text-align: center;'>Enjoy Vision • Este reporte contiene tus valores ópticos para la elaboración de tus lentes.</p>
                            </div>
                        ", function ($message) use ($targetEmail, $name) {
                            $message->to($targetEmail)
                                ->subject("👓 Tu Reporte de Graduación Visual - {$name} | Enjoy Vision");
                        });

                        $record->update(['graduation_email_sent_at' => now()]);

                        Notification::make()
                            ->title('Correo Enviado con Éxito')
                            ->body("Se envió la graduación a {$targetEmail}")
                            ->success()
                            ->send();
                    } catch (Throwable $e) {
                        Notification::make()
                            ->title('Error al enviar correo')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),

            Actions\Action::make('viewDigitalReport')
                ->label('Reporte SpotVision')
                ->icon('heroicon-o-document-text')
                ->color('gray')
                ->url(fn (): string => $this->record->public_report_url, shouldOpenInNewTab: true),

            Actions\DeleteAction::make(),
        ];
    }
}
