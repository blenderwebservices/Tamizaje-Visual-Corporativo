<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Usuario Administrador de Filament
        User::firstOrCreate(
            ['email' => 'admin@visualcorporativo.com'],
            [
                'name' => 'Optometrista Coordinador',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
            ]
        );

        // 2. Empresas de Demostración para Jornadas Corporativas
        $company1 = Company::firstOrCreate(
            ['name' => 'Schneider Electric - Planta Monterrey'],
            [
                'contact_person' => 'Lic. Roberto Morales (RRHH)',
                'contact_phone' => '8181234567',
                'contact_email' => 'roberto.morales@se.com',
                'location' => 'Parque Industrial Apodaca',
                'event_date' => '2026-01-30',
                'notes' => 'Jornada de seguridad industrial y salud visual para 180 colaboradores.',
            ]
        );

        $company2 = Company::firstOrCreate(
            ['name' => 'Ternium México - Planta Churubusco'],
            [
                'contact_person' => 'Ing. Mariana Garza (Seguridad e Higiene)',
                'contact_phone' => '8189876543',
                'contact_email' => 'mgarza@ternium.com',
                'location' => 'San Nicolás de los Garza',
                'event_date' => '2026-02-15',
                'notes' => 'Campamento preventivo para operadores de planta y logística.',
            ]
        );

        // 3. Cliente Registrado Previamente en Fase 1 (Simulación del Formulario de Registro con Teléfono)
        Client::firstOrCreate(
            ['full_name' => 'Izamar Rodriguez Cabello'],
            [
                'uuid' => (string) Str::uuid(),
                'company_id' => $company1->id,
                'client_code' => 'CLI-SCH007',
                'subject_code' => 'ENG7',
                'first_name' => 'Izamar',
                'last_name' => 'Rodriguez Cabello',
                'phone' => '528114567890',
                'email' => 'izamar.rodriguez@empresa.com',
                'birth_date' => '1992-05-05',
                'age' => 33,
                'gender' => 'H',
                'terms_accepted' => true,
                'terms_accepted_at' => now()->subHours(2),
                'crm_stage' => 'screened',
                'purchase_notes' => 'Trabaja en área de ingeniería de procesos, expuesta 8 hrs al día a monitores de control.',
            ]
        );
    }
}
