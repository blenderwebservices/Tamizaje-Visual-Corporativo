<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClientResource\Pages;
use App\Models\Client;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ClientResource extends Resource
{
    protected static ?string $model = Client::class;

    protected static ?string $navigationGroup = 'Fase 1: Atracción y Registro';

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $modelLabel = 'Colaborador / Paciente';

    protected static ?string $pluralModelLabel = 'Colaboradores Registrados';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Datos del Colaborador (Fase 1)')
                    ->description('Información capturada en el formulario digital o extraída del reporte')
                    ->schema([
                        Forms\Components\Select::make('company_id')
                            ->label('Empresa')
                            ->relationship('company', 'name')
                            ->searchable()
                            ->preload(),
                        Forms\Components\TextInput::make('client_code')
                            ->label('Clave de Cliente')
                            ->disabled()
                            ->dehydrated(false),
                        Forms\Components\TextInput::make('subject_code')
                            ->label('ID Sujeto SpotVision (ej. ENG7)')
                            ->placeholder('ENG7')
                            ->maxLength(50),
                        Forms\Components\TextInput::make('first_name')
                            ->label('Nombre(s)')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('last_name')
                            ->label('Apellidos')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('full_name')
                            ->label('Nombre Completo')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone')
                            ->label('WhatsApp / Celular')
                            ->tel()
                            ->placeholder('8112345678')
                            ->helperText('Se normaliza a formato E.164')
                            ->maxLength(50),
                        Forms\Components\TextInput::make('email')
                            ->label('Correo Electrónico')
                            ->email()
                            ->maxLength(255),
                        Forms\Components\DatePicker::make('birth_date')
                            ->label('Fecha de Nacimiento')
                            ->helperText('Utilizado para discriminar homónimos exactos'),
                        Forms\Components\TextInput::make('age')
                            ->label('Edad (Años)')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(120),
                        Forms\Components\Select::make('gender')
                            ->label('Sexo')
                            ->options([
                                'H' => 'Hombre (H)',
                                'M' => 'Mujer (M)',
                                'O' => 'Otro',
                            ]),
                        Forms\Components\Toggle::make('terms_accepted')
                            ->label('Consentimiento Legal y Aviso de Privacidad Aceptado')
                            ->default(true),
                    ])->columns(3),

                Forms\Components\Section::make('Clasificación CRM y Ventas en Sitio (Fase 4)')
                    ->schema([
                        Forms\Components\Select::make('crm_stage')
                            ->label('Etapa de Clasificación CRM')
                            ->options([
                                'prospect' => '1. Prospecto Registrado (Pendiente Tamizaje)',
                                'screened' => '2. Tamizaje Realizado',
                                'purchased_onsite' => '3. Compra en Sitio (Cliente Activo)',
                                'requires_glasses_pending' => '4. Requiere Lentes - Sin Compra (Retargeting Prioritario)',
                                'pass_preventive' => '5. Pasa - Sin Requerimiento (Preventivo)',
                                'lost' => '6. Sin Interés',
                            ])
                            ->required()
                            ->default('prospect'),
                        Forms\Components\Textarea::make('purchase_notes')
                            ->label('Notas de Asesoría y Venta')
                            ->columnSpanFull(),
                    ])->columns(2),
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
                Tables\Columns\TextColumn::make('full_name')
                    ->label('Nombre del Paciente')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('phone')
                    ->label('WhatsApp')
                    ->searchable()
                    ->icon('heroicon-o-phone'),
                Tables\Columns\TextColumn::make('birth_date')
                    ->label('Nacimiento')
                    ->date('d/m/Y')
                    ->description(fn (Client $record) => $record->age ? "{$record->age} años" : null),
                Tables\Columns\TextColumn::make('company.name')
                    ->label('Empresa')
                    ->searchable()
                    ->badge()
                    ->color('gray'),
                Tables\Columns\TextColumn::make('crm_stage')
                    ->label('Clasificación CRM (Fase 4)')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'purchased_onsite' => '🛒 Compra en Sitio (Activo)',
                        'requires_glasses_pending' => '👓 Requiere Lentes (Sin Compra)',
                        'pass_preventive' => '✅ Pasa (Preventivo)',
                        'screened' => '🔬 Tamizado',
                        'prospect' => '📋 Registrado',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'purchased_onsite' => 'success',
                        'requires_glasses_pending' => 'danger',
                        'pass_preventive' => 'info',
                        'screened' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\IconColumn::make('terms_accepted')
                    ->label('Consentimiento')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('company')
                    ->relationship('company', 'name')
                    ->label('Filtrar por Empresa'),
                Tables\Filters\SelectFilter::make('crm_stage')
                    ->label('Etapa CRM')
                    ->options([
                        'purchased_onsite' => 'Compra en Sitio',
                        'requires_glasses_pending' => 'Requiere Lentes - Sin Compra',
                        'pass_preventive' => 'Pasa - Sin Requerimiento',
                        'screened' => 'Tamizaje Realizado',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('openWhatsApp')
                    ->label('WhatsApp')
                    ->icon('heroicon-o-chat-bubble-left-ellipsis')
                    ->color('success')
                    ->url(function (Client $record) {
                        $latest = $record->latestScreening;
                        return $latest ? $latest->whatsapp_url : ($record->phone ? 'https://wa.me/' . Client::sanitizePhone($record->phone) : null);
                    }, shouldOpenInNewTab: true)
                    ->visible(fn (Client $record) => !empty($record->phone)),

                Tables\Actions\Action::make('registerOrder')
                    ->label('Cerrar Venta')
                    ->icon('heroicon-o-shopping-bag')
                    ->color('warning')
                    ->form([
                        Forms\Components\TextInput::make('order_number')
                            ->label('Número de Orden / Ticket')
                            ->default(fn () => 'ORD-' . strtoupper(Str::random(6)))
                            ->required(),
                        Forms\Components\Select::make('product_type')
                            ->label('Tipo de Producto Vendido')
                            ->options([
                                'safety_glasses' => 'Lentes de Seguridad Industrial Graduados',
                                'prescription_glasses' => 'Lentes Oftálmicos Graduados',
                                'blue_light' => 'Lentes Antirreflejantes / Protección Luz Azul',
                                'accessories' => 'Accesorios / Limpieza',
                            ])
                            ->default('safety_glasses')
                            ->required(),
                        Forms\Components\TextInput::make('frame_model')
                            ->label('Modelo de Armazón')
                            ->placeholder('Ej: Armazón Táctico 3M / Carrera Pro'),
                        Forms\Components\Select::make('lens_type')
                            ->label('Tipo de Mica / Tratamiento')
                            ->options([
                                'Policarbonato Alto Impacto' => 'Policarbonato Alto Impacto',
                                'Antirreflejante AR Premium' => 'Antirreflejante AR Premium',
                                'Blue Defense (Protección Pantallas)' => 'Blue Defense (Protección Pantallas)',
                                'Fotocromático Transitions' => 'Fotocromático Transitions',
                            ])
                            ->default('Policarbonato Alto Impacto'),
                        Forms\Components\TextInput::make('total_amount')
                            ->label('Monto Total ($ MXN)')
                            ->numeric()
                            ->prefix('$')
                            ->default(1500.00)
                            ->required(),
                        Forms\Components\Select::make('payment_method')
                            ->label('Método de Pago')
                            ->options([
                                'payroll_deduction' => 'Descuento Vía Nómina (Convenio Empresa)',
                                'card' => 'Tarjeta de Débito / Crédito',
                                'cash' => 'Efectivo',
                                'transfer' => 'Transferencia Bancaria',
                            ])
                            ->default('payroll_deduction')
                            ->required(),
                        Forms\Components\Textarea::make('notes')
                            ->label('Observaciones de Graduación o Entrega'),
                    ])
                    ->action(function (Client $record, array $data) {
                        $latestScreening = $record->latestScreening;
                        Order::create([
                            'client_id' => $record->id,
                            'screening_id' => $latestScreening?->id,
                            'order_number' => $data['order_number'],
                            'product_type' => $data['product_type'],
                            'frame_model' => $data['frame_model'] ?? null,
                            'lens_type' => $data['lens_type'] ?? null,
                            'total_amount' => $data['total_amount'],
                            'payment_method' => $data['payment_method'],
                            'status' => 'completed',
                            'notes' => $data['notes'] ?? null,
                        ]);

                        $record->update([
                            'crm_stage' => 'purchased_onsite',
                            'purchase_notes' => 'Venta cerrada en jornada: ' . $data['order_number'] . ' ($' . $data['total_amount'] . ')',
                        ]);

                        Notification::make()
                            ->title('Venta en Sitio Registrada')
                            ->body("El colaborador {$record->full_name} fue clasificado como Cliente Activo.")
                            ->success()
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
            'index' => Pages\ListClients::route('/'),
            'create' => Pages\CreateClient::route('/create'),
            'edit' => Pages\EditClient::route('/{record}/edit'),
        ];
    }
}
