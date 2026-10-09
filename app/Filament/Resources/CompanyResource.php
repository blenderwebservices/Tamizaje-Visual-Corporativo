<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompanyResource\Pages;
use App\Models\Company;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CompanyResource extends Resource
{
    protected static ?string $model = Company::class;

    protected static ?string $navigationGroup = 'Fase 1: Atracción y Registro';

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $modelLabel = 'Empresa';

    protected static ?string $pluralModelLabel = 'Empresas y Eventos';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información del Evento Corporativo')
                    ->description('Datos de la empresa y coordinación de la jornada de salud visual')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nombre de la Empresa')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\DatePicker::make('event_date')
                            ->label('Fecha de la Jornada')
                            ->default(now()),
                        Forms\Components\TextInput::make('location')
                            ->label('Ubicación / Planta')
                            ->placeholder('Ej: Planta Apodaca, Edificio B')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('contact_person')
                            ->label('Contacto Responsable (RRHH / EHS)')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('contact_phone')
                            ->label('Teléfono de Contacto')
                            ->tel()
                            ->maxLength(50),
                        Forms\Components\TextInput::make('contact_email')
                            ->label('Correo Electrónico')
                            ->email()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('notes')
                            ->label('Notas u Objetivos del Evento')
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Empresa')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('location')
                    ->label('Ubicación')
                    ->searchable(),
                Tables\Columns\TextColumn::make('event_date')
                    ->label('Fecha')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('contact_person')
                    ->label('Contacto RRHH')
                    ->searchable(),
                Tables\Columns\TextColumn::make('clients_count')
                    ->label('Registrados')
                    ->counts('clients')
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('screenings_count')
                    ->label('Tamizajes SpotVision')
                    ->counts('screenings')
                    ->badge()
                    ->color('success'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('copyRegistrationLink')
                    ->label('Link de Registro')
                    ->icon('heroicon-o-link')
                    ->color('primary')
                    ->action(function (Company $record) {
                        Notification::make()
                            ->title('Enlace de Registro Móvil')
                            ->body(url('/registro?empresa=' . $record->id))
                            ->success()
                            ->send();
                    })
                    ->extraAttributes(fn (Company $record) => [
                        'onclick' => "navigator.clipboard.writeText('" . url('/registro?empresa=' . $record->id) . "')",
                    ]),
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

    public static function canViewAny(): bool
    {
        $user = auth()->user();
        return $user && ($user->isAdmin() || $user->isEmpresarial());
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }

    public static function canEdit(Model $record): bool
    {
        $user = auth()->user();
        if (! $user) {
            return false;
        }

        if ($user->isAdmin()) {
            return true;
        }

        return $user->isEmpresarial() && $user->company_id === $record->id;
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();

        if (! $user || $user->isAdmin()) {
            return $query;
        }

        if ($user->isEmpresarial()) {
            return $query->where('id', $user->company_id);
        }

        return $query->whereRaw('0 = 1');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCompanies::route('/'),
            'create' => Pages\CreateCompany::route('/create'),
            'edit' => Pages\EditCompany::route('/{record}/edit'),
        ];
    }
}
