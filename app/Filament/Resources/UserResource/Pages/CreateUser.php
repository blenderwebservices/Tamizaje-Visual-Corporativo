<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = auth()->user();

        // Si el creador es empresarial, fuerza la empresa del usuario
        if ($user && $user->isEmpresarial()) {
            $data['company_id'] = $user->company_id;
            // Un usuario empresarial no puede crear un administrador
            if (($data['role'] ?? null) === \App\Models\User::ROLE_ADMIN) {
                $data['role'] = \App\Models\User::ROLE_USER;
            }
        }

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
