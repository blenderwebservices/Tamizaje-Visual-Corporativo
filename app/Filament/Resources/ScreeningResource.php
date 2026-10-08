<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ScreeningResource\Pages;
use App\Models\Screening;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

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
                                            if (!$record || !$record->preview_image_path) {
                                                return 'Sin imagen de previsualización disponible.';
                                            }
                                            $url = asset('storage/' . $record->preview_image_path);
                                            return new \Illuminate\Support\HtmlString(
                                                '<div class="rounded-lg overflow-hidden border border-gray-300 dark:border-gray-700 shadow-sm">' .
                                                '<img src="' . e($url) . '" alt="Reporte SpotVision" class="w-full h-auto object-contain max-h-[600px]" />' .
                                                '</div>'
                                            );
                                        }),
                                    Forms\Components\TextInput::make('barcode_code')
                                        ->label('Código de Barras / Trazabilidad')
                                        ->disabled(),
                                    Forms\Components\TextInput::make('extraction_method')
                                        ->label('Método de Extracción')
                                        ->badge()
                                        ->disabled(),
                                ]),
                        ])->columnSpan(1),

                        // Columna Derecha: Mediciones Optométricas y Análisis
                        Forms\Components\Group::make([
                            Forms\Components\Section::make('Datos del Paciente y Estado')
                                ->schema([
                                    Forms\Components\Select::make('client_id')
                                        ->label('Colaborador / Paciente')
                                        ->relationship('client', 'full_name')
                                        ->searchable()
                                        ->required(),
                                    Forms\Components\Select::make('company_id')
                                        ->label('Empresa / Jornada')
                                        ->relationship('company', 'name')
                                        ->searchable(),
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
                                ])->columns(3),

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
                    ->description(fn (Screening $record) => $record->client?->phone ? "📱 " . $record->client->phone : "Sin teléfono"),
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
                Tables\Actions\Action::make('sendWhatsApp')
                    ->label('Enviar WhatsApp')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->color('success')
                    ->url(fn (Screening $record) => $record->whatsapp_url, shouldOpenInNewTab: true)
                    ->visible(fn (Screening $record) => !empty($record->client?->phone))
                    ->action(function (Screening $record) {
                        $record->update([
                            'whatsapp_status' => 'sent',
                            'whatsapp_sent_at' => now(),
                        ]);
                        Notification::make()
                            ->title('WhatsApp Registrado')
                            ->body('Se actualizó el estado a Enviado.')
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('viewDigitalReport')
                    ->label('Reporte Digital')
                    ->icon('heroicon-o-document-text')
                    ->color('info')
                    ->url(fn (Screening $record) => $record->public_report_url, shouldOpenInNewTab: true),

                Tables\Actions\Action::make('downloadPdf')
                    ->label('PDF Original')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->action(function (Screening $record) {
                        $fullPath = \App\Services\SpotVisionImporter::resolvePath($record->original_pdf_path);
                        if (file_exists($fullPath)) {
                            return response()->download($fullPath, $record->original_filename);
                        }
                        Notification::make()
                            ->title('Archivo no encontrado')
                            ->danger()
                            ->send();
                    }),

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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListScreenings::route('/'),
            'create' => Pages\CreateScreening::route('/create'),
            'edit' => Pages\EditScreening::route('/{record}/edit'),
        ];
    }
}
