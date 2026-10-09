<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationGroup = 'Administración y Usuarios';

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $modelLabel = 'Usuario';

    protected static ?string $pluralModelLabel = 'Usuarios del Sistema';

    protected static ?int $navigationSort = 10;

    public static function canViewAny(): bool
    {
        $user = auth()->user();
        return $user && ($user->isAdmin() || $user->isEmpresarial());
    }

    public static function canCreate(): bool
    {
        $user = auth()->user();
        return $user && ($user->isAdmin() || $user->isEmpresarial());
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

        if ($user->isEmpresarial()) {
            return $record->company_id === $user->company_id && ! $record->isAdmin();
        }

        return false;
    }

    public static function canDelete(Model $record): bool
    {
        $user = auth()->user();
        if (! $user || $record->id === $user->id) {
            return false;
        }

        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isEmpresarial()) {
            return $record->company_id === $user->company_id && ! $record->isAdmin();
        }

        return false;
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

        return $query->whereRaw('0 = 1');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Datos del Usuario')
                    ->description('Gestión de cuentas de acceso, roles y asignación de grupo empresarial')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nombre Completo')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('email')
                            ->label('Correo Electrónico')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        Forms\Components\Select::make('role')
                            ->label('Rol del Usuario')
                            ->options(function () {
                                $user = auth()->user();
                                if ($user?->isAdmin()) {
                                    return [
                                        User::ROLE_ADMIN => 'Administrador Global (Acceso Total)',
                                        User::ROLE_EMPRESARIAL => 'Empresarial (Acceso a Grupo de Empresa)',
                                        User::ROLE_USER => 'Usuario Estándar (Acceso a sus propios registros)',
                                    ];
                                }
                                return [
                                    User::ROLE_EMPRESARIAL => 'Empresarial (Acceso a Grupo de Empresa)',
                                    User::ROLE_USER => 'Usuario Operador (Acceso a sus propios registros)',
                                ];
                            })
                            ->default(fn () => auth()->user()?->isEmpresarial() ? User::ROLE_USER : User::ROLE_USER)
                            ->required(),

                        Forms\Components\Select::make('company_id')
                            ->label('Empresa Asociada')
                            ->relationship('company', 'name')
                            ->searchable()
                            ->preload()
                            ->default(fn () => auth()->user()?->company_id)
                            ->disabled(fn () => ! auth()->user()?->isAdmin())
                            ->dehydrated()
                            ->helperText(fn () => auth()->user()?->isEmpresarial() ? 'Asignado automáticamente a su empresa.' : 'Dejar vacío si es usuario global independiente.'),

                        Forms\Components\TextInput::make('password')
                            ->label('Contraseña')
                            ->password()
                            ->dehydrateStateUsing(fn ($state) => filled($state) ? Hash::make($state) : null)
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->maxLength(255)
                            ->helperText(fn (string $operation): ?string => $operation === 'edit' ? 'Dejar en blanco para conservar la contraseña actual.' : null),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('email')
                    ->label('Correo Electrónico')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('role')
                    ->label('Rol')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        User::ROLE_ADMIN => 'Administrador Global',
                        User::ROLE_EMPRESARIAL => 'Empresarial',
                        User::ROLE_USER => 'Usuario Estándar',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        User::ROLE_ADMIN => 'danger',
                        User::ROLE_EMPRESARIAL => 'info',
                        User::ROLE_USER => 'success',
                        default => 'gray',
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('company.name')
                    ->label('Empresa')
                    ->searchable()
                    ->sortable()
                    ->placeholder('N/A (Global / Independiente)'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha de Creación')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->label('Rol')
                    ->options([
                        User::ROLE_ADMIN => 'Administrador Global',
                        User::ROLE_EMPRESARIAL => 'Empresarial',
                        User::ROLE_USER => 'Usuario Estándar',
                    ])
                    ->visible(fn () => auth()->user()?->isAdmin() ?? false),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn (User $record) => $record->id !== auth()->id()),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->action(function ($records) {
                            $currentUserId = auth()->id();
                            $records->each(function (User $record) use ($currentUserId) {
                                if ($record->id !== $currentUserId) {
                                    $record->delete();
                                }
                            });
                        }),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
