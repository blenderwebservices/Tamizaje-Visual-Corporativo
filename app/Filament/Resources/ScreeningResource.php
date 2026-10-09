<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ScreeningResource\Pages;
use App\Models\Screening;
use App\Services\SpotVisionImporter;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;

class ScreeningResource extends Resource
{
    protected static ?string $model = Screening::class;

    protected static ?string $navigationGroup = 'Fase 2: Tamizaje SpotVision';

    protected static ?string $navigationIcon = 'heroicon-o-eye';

    protected static ?string $modelLabel = 'Tamizaje SpotVision';

    protected static ?string $pluralModelLabel = 'Estudios SpotVision';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(3)
                    ->schema([
                        // Columna Izquierda: Previsualización del Escaneo Original
                        Forms\Components\Group::make([
                            Forms\Components\Section::make('Escaneo Original SpotVision')
                                ->description('Imagen recuperada del PDF impreso por el dispositivo')
                                ->schema([
                                    Forms\Components\Placeholder::make('preview_image')
                                        ->label('Comprobante Visual')
                                        ->content(function (?Screening $record) {
                                            if (! $record || ! $record->preview_image_path) {
                                                return 'Sin imagen de previsualización disponible.';
                                            }
                                            $url = asset('storage/'.$record->preview_image_path);

                                            return new HtmlString(
                                                '<div class="rounded-lg overflow-hidden border border-gray-300 dark:border-gray-700 shadow-sm">'.
                                                '<img src="'.e($url).'" alt="Reporte SpotVision" class="w-full h-auto object-contain max-h-[600px]" />'.
                                                '</div>'
                                            );
                                        }),
                                    Forms\Components\FileUpload::make('preview_image_path')
                                        ->label('Comprobante / Foto (Opcional)')
                                        ->image()
                                        ->disk('public')
                                        ->directory('screenings_previews')
                                        ->visible(fn (string $operation) => $operation === 'create'),
                                    Forms\Components\TextInput::make('barcode_code')
                                        ->label('Código de Barras / Trazabilidad')
                                        ->placeholder('Generado automáticamente')
                                        ->disabled(),
                                    Forms\Components\TextInput::make('extraction_method')
                                        ->label('Método de Extracción')
                                        ->default('manual')
                                        ->disabled()
                                        ->dehydrated(),
                                ]),
                        ])->columnSpan(1),

                        // Columna Derecha: Mediciones Optométricas y Análisis
                        Forms\Components\Group::make([
                            Forms\Components\Section::make('Datos del Paciente y Estado')
                                ->schema([
                                    Forms\Components\Select::make('client_id')
                                        ->label('Colaborador / Paciente')
                                        ->relationship('client', 'full_name', modifyQueryUsing: function (Builder $query) {
                                            $user = auth()->user();
                                            if (! $user || $user->isAdmin()) {
                                                return $query;
                                            }
                                            if ($user->isEmpresarial()) {
                                                return $query->where('company_id', $user->company_id);
                                            }
                                            return $query->where('user_id', $user->id);
                                        })
                                        ->searchable()
                                        ->required(),
                                    Forms\Components\Select::make('company_id')
                                        ->label('Empresa / Jornada')
                                        ->relationship('company', 'name', modifyQueryUsing: function (Builder $query) {
                                            $user = auth()->user();
                                            if (! $user || $user->isAdmin()) {
                                                return $query;
                                            }
                                            if ($user->isEmpresarial()) {
                                                return $query->where('id', $user->company_id);
                                            }
                                            return $query->whereRaw('0 = 1');
                                        })
                                        ->searchable()
                                        ->default(fn () => auth()->user()?->company_id)
                                        ->disabled(fn () => ! auth()->user()?->isAdmin())
                                        ->dehydrated(),
                                    Forms\Components\TextInput::make('subject_code')
                                        ->label('ID Sujeto (SpotVision)')
                                        ->maxLength(50),
                                    Forms\Components\DateTimePicker::make('exam_date')
                                        ->label('Fecha y Hora del Examen')
                                        ->default(now()),
                                    Forms\Components\Select::make('screening_status')
                                        ->label('Resultado General (Fase 2)')
                                        ->options([
                                            'pass' => '✅ PASA (Mediciones en Rangos Normales)',
                                            'refer' => '⚠️ REMITIR (Requiere Valoración Optométrica)',
                                            'incomplete' => '❌ Incompleto',
                                        ])
                                        ->default('refer')
                                        ->required(),
                                    Forms\Components\TextInput::make('status_label')
                                        ->label('Etiqueta del Dispositivo')
                                        ->maxLength(255),
                                    Forms\Components\Toggle::make('wears_glasses')
                                        ->label('¿Usa Gafas en el Examen?'),
                                    Forms\Components\TextInput::make('interpupillary_distance_mm')
                                        ->label('Distancia Interpupilar (mm)')
                                        ->numeric()
                                        ->suffix('mm'),
                                ])->columns(2),

                            Forms\Components\Section::make('Refracción Ojo Derecho (OD)')
                                ->collapsible()
                                ->schema([
                                    Forms\Components\TextInput::make('od_sphere_se')
                                        ->label('Esfera Equiv. (SE)')
                                        ->numeric(),
                                    Forms\Components\TextInput::make('od_sphere_ds')
                                        ->label('Esfera (DS)')
                                        ->numeric(),
                                    Forms\Components\TextInput::make('od_cylinder_dc')
                                        ->label('Cilindro (DC)')
                                        ->numeric(),
                                    Forms\Components\TextInput::make('od_axis')
                                        ->label('Eje (°)')
                                        ->numeric()
                                        ->suffix('°'),
                                    Forms\Components\TextInput::make('od_pupil_size_mm')
                                        ->label('Diámetro Pupilar')
                                        ->numeric()
                                        ->suffix('mm'),
                                    Forms\Components\TextInput::make('od_gaze_v')
                                        ->label('Alineación Vertical'),
                                    Forms\Components\TextInput::make('od_gaze_h')
                                        ->label('Alineación Horizontal'),
                                    Forms\Components\TextInput::make('od_dnp_mm')
                                        ->label('DNP (Dist. Naso Pupilar)')
                                        ->numeric()
                                        ->minValue(18.0)
                                        ->maxValue(38.5)
                                        ->step(0.5)
                                        ->suffix('mm')
                                        ->helperText('Rango [18.0 .. 38.5] mm, escala 0.5'),
                                    Forms\Components\TextInput::make('od_add')
                                        ->label('Adición / ADD (Cerca)')
                                        ->numeric()
                                        ->minValue(0.75)
                                        ->maxValue(3.50)
                                        ->step(0.25)
                                        ->prefix('+')
                                        ->helperText('Rango [+0.75 .. +3.50], escala 0.25'),
                                ])->columns(3),

                            Forms\Components\Section::make('Refracción Ojo Izquierdo (OS)')
                                ->collapsible()
                                ->schema([
                                    Forms\Components\TextInput::make('os_sphere_se')
                                        ->label('Esfera Equiv. (SE)')
                                        ->numeric(),
                                    Forms\Components\TextInput::make('os_sphere_ds')
                                        ->label('Esfera (DS)')
                                        ->numeric(),
                                    Forms\Components\TextInput::make('os_cylinder_dc')
                                        ->label('Cilindro (DC)')
                                        ->numeric(),
                                    Forms\Components\TextInput::make('os_axis')
                                        ->label('Eje (°)')
                                        ->numeric()
                                        ->suffix('°'),
                                    Forms\Components\TextInput::make('os_pupil_size_mm')
                                        ->label('Diámetro Pupilar')
                                        ->numeric()
                                        ->suffix('mm'),
                                    Forms\Components\TextInput::make('os_gaze_v')
                                        ->label('Alineación Vertical'),
                                    Forms\Components\TextInput::make('os_gaze_h')
                                        ->label('Alineación Horizontal'),
                                    Forms\Components\TextInput::make('os_dnp_mm')
                                        ->label('DNP (Dist. Naso Pupilar)')
                                        ->numeric()
                                        ->minValue(18.0)
                                        ->maxValue(38.5)
                                        ->step(0.5)
                                        ->suffix('mm')
                                        ->helperText('Rango [18.0 .. 38.5] mm, escala 0.5'),
                                    Forms\Components\TextInput::make('os_add')
                                        ->label('Adición / ADD (Cerca)')
                                        ->numeric()
                                        ->minValue(0.75)
                                        ->maxValue(3.50)
                                        ->step(0.25)
                                        ->prefix('+')
                                        ->helperText('Rango [+0.75 .. +3.50], escala 0.25'),
                                ])->columns(3),

                            Forms\Components\Section::make('Reporte Clínico de Graduación y Optometría (Enjoy Vision)')
                                ->collapsible()
                                ->schema([
                                    Forms\Components\TextInput::make('optometrist_name')
                                        ->label('Optometrista / Especialista Evaluador')
                                        ->placeholder('Ej. Lic. Opt. Francisco / Enjoy Vision')
                                        ->maxLength(150),
                                    Forms\Components\Textarea::make('optometrist_notes')
                                        ->label('Observaciones Clínicas y Notas de Graduación')
                                        ->rows(3)
                                        ->placeholder('Notas de adaptación, confirmación de cilindro/eje, recomendación de micas (monofocal, progresivo, etc.).'),
                                ])->columns(1),

                            Forms\Components\Section::make('Análisis Rápido y Comunicación (Fase 3)')
                                ->schema([
                                    Forms\Components\TagsInput::make('findings')
                                        ->label('Afecciones Detectadas')
                                        ->suggestions(['Miopía', 'Astigmatismo', 'Hipermetropía', 'Anisometropía', 'Anisocoria']),
                                    Forms\Components\Textarea::make('quick_analysis_summary')
                                        ->label('Síntesis Rápida para el Paciente')
                                        ->rows(3),
                                    Forms\Components\Textarea::make('recommendations')
                                        ->label('Recomendaciones Optométricas')
                                        ->rows(2),
                                    Forms\Components\Textarea::make('whatsapp_message_body')
                                        ->label('Plantilla de Mensaje WhatsApp')
                                        ->rows(4),
                                ]),
                        ])->columnSpan(2),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('subject_code')
                    ->label('ID Sujeto')
                    ->badge()
                    ->color('info')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('client.full_name')
                    ->label('Colaborador')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn (Screening $record) => $record->client?->phone ? '📱 '.$record->client->phone : 'Sin teléfono'),
                Tables\Columns\TextColumn::make('exam_date')
                    ->label('Fecha Examen')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('screening_status')
                    ->label('Resultado')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pass' => '✅ PASA',
                        'refer' => '⚠️ REMITIR',
                        default => 'INCOMPLETO',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'pass' => 'success',
                        'refer' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('od_refraction')
                    ->label('Ojo Derecho (OD)')
                    ->getStateUsing(function (Screening $record) {
                        $se = $record->od_sphere_se !== null ? number_format($record->od_sphere_se, 2) : '-';
                        $dc = $record->od_cylinder_dc !== null ? number_format($record->od_cylinder_dc, 2) : '-';
                        $ax = $record->od_axis !== null ? " @{$record->od_axis}°" : '';

                        return "SE: {$se} | DC: {$dc}{$ax}";
                    }),
                Tables\Columns\TextColumn::make('os_refraction')
                    ->label('Ojo Izquierdo (OS)')
                    ->getStateUsing(function (Screening $record) {
                        $se = $record->os_sphere_se !== null ? number_format($record->os_sphere_se, 2) : '-';
                        $dc = $record->os_cylinder_dc !== null ? number_format($record->os_cylinder_dc, 2) : '-';
                        $ax = $record->os_axis !== null ? " @{$record->os_axis}°" : '';

                        return "SE: {$se} | DC: {$dc}{$ax}";
                    }),
                Tables\Columns\TextColumn::make('findings')
                    ->label('Afecciones')
                    ->badge()
                    ->color('warning')
                    ->separator(','),
                Tables\Columns\TextColumn::make('whatsapp_status')
                    ->label('WhatsApp')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'sent' => 'Enviado',
                        'failed' => 'Fallido',
                        default => 'Pendiente',
                    })
                    ->color(fn (string $state) => match ($state) {
                        'sent' => 'success',
                        'failed' => 'danger',
                        default => 'amber',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('screening_status')
                    ->label('Resultado')
                    ->options([
                        'pass' => 'PASA',
                        'refer' => 'REMITIR',
                    ]),
                Tables\Filters\SelectFilter::make('company')
                    ->relationship('company', 'name')
                    ->label('Empresa'),
                Tables\Filters\SelectFilter::make('whatsapp_status')
                    ->label('Estado de WhatsApp')
                    ->options([
                        'pending' => 'Pendiente de Envío',
                        'sent' => 'Ya Enviado',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('sendViaCloudApi')
                    ->label('WhatsApp Enjoy Vision (API)')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('success')
                    ->visible(fn (Screening $record) => ! empty($record->client?->phone))
                    ->requiresConfirmation(fn () => ! \App\Services\WhatsAppCloudApiService::isConfigured())
                    ->modalHeading('WhatsApp Cloud API Oficial')
                    ->modalDescription(function () {
                        if (! \App\Services\WhatsAppCloudApiService::isConfigured()) {
                            return 'La API oficial de Enjoy Vision no está configurada aún en .env (requiere WHATSAPP_PHONE_ID y WHATSAPP_ACCESS_TOKEN). Puedes usar el botón de "WhatsApp Web (Operador)" para enviar manualmente mientras tanto.';
                        }
                        return '¿Deseas enviar el reporte oficial de tamizaje automáticamente desde el número corporativo de Enjoy Vision?';
                    })
                    ->modalSubmitActionLabel(fn () => \App\Services\WhatsAppCloudApiService::isConfigured() ? 'Confirmar Envío API' : 'Entendido')
                    ->action(function (Screening $record) {
                        if (! \App\Services\WhatsAppCloudApiService::isConfigured()) {
                            Notification::make()
                                ->title('API no configurada')
                                ->body('Configura WHATSAPP_PHONE_ID y WHATSAPP_ACCESS_TOKEN en tu archivo .env. Mientras tanto, usa la opción "WhatsApp Web (Operador)".')
                                ->warning()
                                ->send();
                            return;
                        }

                        $service = app(\App\Services\WhatsAppCloudApiService::class);
                        $result = $service->sendScreeningReport($record);

                        if ($result['success']) {
                            Notification::make()
                                ->title('Enviado desde Enjoy Vision')
                                ->body($result['message'])
                                ->success()
                                ->send();
                        } else {
                            Notification::make()
                                ->title('Error al enviar por API')
                                ->body($result['message'])
                                ->danger()
                                ->send();
                        }
                    }),

                Tables\Actions\Action::make('sendWhatsApp')
                    ->label('WhatsApp Web (Operador)')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->color('gray')
                    ->url(fn (Screening $record) => $record->whatsapp_url, shouldOpenInNewTab: true)
                    ->visible(fn (Screening $record) => ! empty($record->client?->phone))
                    ->action(function (Screening $record) {
                        $record->update([
                            'whatsapp_status' => 'sent',
                            'whatsapp_sent_at' => now(),
                        ]);
                        Notification::make()
                            ->title('WhatsApp Registrado')
                            ->body('Se abrió el enlace y se actualizó el estado a Enviado.')
                            ->success()
                            ->send();
                    }),

                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('viewGraduation')
                        ->label('Ver Graduación')
                        ->icon('heroicon-o-eye')
                        ->color('indigo')
                        ->url(fn (Screening $record) => $record->graduation_report_url, shouldOpenInNewTab: true),

                    Tables\Actions\Action::make('sendGraduationWhatsApp')
                        ->label('WhatsApp Graduación')
                        ->icon('heroicon-o-chat-bubble-bottom-center-text')
                        ->color('success')
                        ->visible(fn (Screening $record) => ! empty($record->client?->phone))
                        ->action(function (Screening $record) {
                            if (\App\Services\WhatsAppCloudApiService::isConfigured()) {
                                $service = app(\App\Services\WhatsAppCloudApiService::class);
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
                                ->body('Se abrió el reporte de graduación para envío desde el navegador.')
                                ->info()
                                ->send();

                            return redirect()->away($record->graduation_whatsapp_url);
                        }),

                    Tables\Actions\Action::make('sendGraduationEmail')
                        ->label('Correo Graduación')
                        ->icon('heroicon-o-envelope')
                        ->color('warning')
                        ->visible(fn (Screening $record) => ! empty($record->client?->email))
                        ->form([
                            Forms\Components\TextInput::make('email')
                                ->label('Correo del Paciente')
                                ->default(fn (Screening $record) => $record->client?->email)
                                ->email()
                                ->required(),
                        ])
                        ->action(function (Screening $record, array $data) {
                            $targetEmail = $data['email'];
                            $name = $record->client?->full_name ?? 'Paciente';
                            $gradUrl = $record->graduation_report_url;

                            try {
                                \Illuminate\Support\Facades\Mail::html("
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
                            } catch (\Throwable $e) {
                                Notification::make()
                                    ->title('Error al enviar correo')
                                    ->body($e->getMessage())
                                    ->danger()
                                    ->send();
                            }
                        }),

                    Tables\Actions\Action::make('viewDigitalReport')
                        ->label('Reporte SpotVision')
                        ->icon('heroicon-o-document-text')
                        ->color('info')
                        ->url(fn (Screening $record) => $record->public_report_url, shouldOpenInNewTab: true),

                    Tables\Actions\Action::make('downloadPdf')
                        ->label('PDF Original SpotVision')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->color('gray')
                        ->action(function (Screening $record) {
                            $fullPath = SpotVisionImporter::resolvePath($record->original_pdf_path);
                            if (file_exists($fullPath)) {
                                return response()->download($fullPath, $record->original_filename);
                            }
                            Notification::make()
                                ->title('Archivo no encontrado')
                                ->danger()
                                ->send();
                        }),
                ])
                ->label('Reportes & Graduación')
                ->icon('heroicon-m-ellipsis-vertical')
                ->color('primary'),

                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();

        if (! $user || $user->isAdmin()) {
            return $query;
        }

        if ($user->isEmpresarial()) {
            return $query->where('company_id', $user->company_id);
        }

        return $query->where('user_id', $user->id);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListScreenings::route('/'),
            'create' => Pages\CreateScreening::route('/create'),
            'edit' => Pages\EditScreening::route('/{record}/edit'),
        ];
    }
}
