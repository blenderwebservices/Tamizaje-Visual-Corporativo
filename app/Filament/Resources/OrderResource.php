<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationGroup = 'Fase 4: Cierre y CRM en Sitio';

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $modelLabel = 'Orden de Venta en Sitio';

    protected static ?string $pluralModelLabel = 'Órdenes en Sitio (Clientes Activos)';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Cierre de Venta Inmediato (Fase 4)')
                    ->description('Registro de compra de armazones, micas o lentes de seguridad en jornada')
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
                        Forms\Components\Select::make('screening_id')
                            ->label('Tamizaje SpotVision Asociado')
                            ->relationship('screening', 'barcode_code', modifyQueryUsing: function (Builder $query) {
                                $user = auth()->user();
                                if (! $user || $user->isAdmin()) {
                                    return $query;
                                }
                                if ($user->isEmpresarial()) {
                                    return $query->where('company_id', $user->company_id);
                                }
                                return $query->where('user_id', $user->id);
                            })
                            ->searchable(),
                        Forms\Components\TextInput::make('order_number')
                            ->label('Folio de Venta')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('product_type')
                            ->label('Tipo de Producto')
                            ->options([
                                'safety_glasses' => 'Lentes de Seguridad Industrial Graduados',
                                'prescription_glasses' => 'Lentes Oftálmicos Graduados',
                                'blue_light' => 'Lentes de Descanso / Luz Azul',
                                'accessories' => 'Accesorios / Paquete de Cuidado',
                            ])
                            ->default('safety_glasses')
                            ->required(),
                        Forms\Components\TextInput::make('frame_model')
                            ->label('Modelo de Armazón')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('lens_type')
                            ->label('Tipo de Mica / Tratamiento')
                            ->placeholder('Policarbonato AR, Blue Ray, etc.')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('total_amount')
                            ->label('Importe Total')
                            ->numeric()
                            ->prefix('$')
                            ->required(),
                        Forms\Components\Select::make('payment_method')
                            ->label('Forma de Pago')
                            ->options([
                                'payroll_deduction' => 'Descuento Vía Nómina (Empresa)',
                                'card' => 'Tarjeta Débito / Crédito',
                                'cash' => 'Efectivo',
                                'transfer' => 'Transferencia',
                            ])
                            ->default('payroll_deduction'),
                        Forms\Components\Select::make('status')
                            ->label('Estado de la Orden')
                            ->options([
                                'completed' => 'Cerrada / Pagada',
                                'in_process' => 'En Laboratorio Óptico',
                                'delivered' => 'Entregada al Colaborador',
                            ])
                            ->default('completed')
                            ->required(),
                        Forms\Components\Textarea::make('notes')
                            ->label('Notas de Graduación o Especificaciones')
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label('Folio')
                    ->weight('bold')
                    ->searchable(),
                Tables\Columns\TextColumn::make('client.full_name')
                    ->label('Colaborador')
                    ->searchable(),
                Tables\Columns\TextColumn::make('product_type')
                    ->label('Producto')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'safety_glasses' => '🥽 Lentes Seguridad',
                        'prescription_glasses' => '👓 Lentes Oftálmicos',
                        'blue_light' => '💻 Luz Azul',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('frame_model')
                    ->label('Armazón')
                    ->searchable(),
                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Monto')
                    ->money('MXN')
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Método de Pago')
                    ->badge()
                    ->color('info')
                    ->formatStateUsing(fn (?string $state) => match ($state) {
                        'payroll_deduction' => 'Vía Nómina',
                        'card' => 'Tarjeta',
                        'cash' => 'Efectivo',
                        default => $state ?? '-',
                    }),
                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'completed' => 'success',
                        'delivered' => 'info',
                        default => 'warning',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('product_type')
                    ->options([
                        'safety_glasses' => 'Lentes de Seguridad',
                        'prescription_glasses' => 'Lentes Graduados',
                    ]),
            ])
            ->actions([
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
            return $query->whereHas('client', fn (Builder $q) => $q->where('company_id', $user->company_id));
        }

        return $query->whereHas('client', fn (Builder $q) => $q->where('user_id', $user->id));
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
