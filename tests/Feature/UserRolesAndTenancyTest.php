<?php

namespace Tests\Feature;

use App\Filament\Resources\ClientResource;
use App\Filament\Resources\CompanyResource;
use App\Filament\Resources\ScreeningResource;
use App\Filament\Resources\UserResource;
use App\Models\Client;
use App\Models\Company;
use App\Models\Screening;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRolesAndTenancyTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected Company $companyA;
    protected Company $companyB;
    protected User $empresarialA;
    protected User $operadorA1;
    protected User $operadorA2;
    protected User $independentUser;
    protected Client $clientA1;
    protected Client $clientA2;
    protected Client $clientB1;
    protected Screening $screeningA1;
    protected Screening $screeningA2;
    protected Screening $screeningB1;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Admin
        $this->admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => bcrypt('secret'),
            'role' => User::ROLE_ADMIN,
        ]);

        // 2. Empresas
        $this->companyA = Company::create(['name' => 'Empresa Alfa']);
        $this->companyB = Company::create(['name' => 'Empresa Beta']);

        // 3. Usuario Empresarial Alfa
        $this->empresarialA = User::create([
            'name' => 'Admin Alfa',
            'email' => 'admin@alfa.com',
            'password' => bcrypt('secret'),
            'role' => User::ROLE_EMPRESARIAL,
            'company_id' => $this->companyA->id,
        ]);

        // 4. Operador 1 de Empresa Alfa (rol user)
        $this->operadorA1 = User::create([
            'name' => 'Operador 1 Alfa',
            'email' => 'op1@alfa.com',
            'password' => bcrypt('secret'),
            'role' => User::ROLE_USER,
            'company_id' => $this->companyA->id,
        ]);

        // 5. Operador 2 de Empresa Alfa (rol user)
        $this->operadorA2 = User::create([
            'name' => 'Operador 2 Alfa',
            'email' => 'op2@alfa.com',
            'password' => bcrypt('secret'),
            'role' => User::ROLE_USER,
            'company_id' => $this->companyA->id,
        ]);

        // 6. Usuario Independiente
        $this->independentUser = User::create([
            'name' => 'User Independiente',
            'email' => 'user@independiente.com',
            'password' => bcrypt('secret'),
            'role' => User::ROLE_USER,
            'company_id' => null,
        ]);

        // 7. Clientes y Estudios de Alfa (creados por Operador A1 y Operador A2)
        $this->clientA1 = Client::create([
            'company_id' => $this->companyA->id,
            'user_id' => $this->operadorA1->id,
            'full_name' => 'Paciente Alfa 1',
            'phone' => '8111111111',
        ]);
        $this->screeningA1 = Screening::create([
            'client_id' => $this->clientA1->id,
            'company_id' => $this->companyA->id,
            'user_id' => $this->operadorA1->id,
            'subject_code' => 'ALFA1',
        ]);

        $this->clientA2 = Client::create([
            'company_id' => $this->companyA->id,
            'user_id' => $this->operadorA2->id,
            'full_name' => 'Paciente Alfa 2',
            'phone' => '8122222222',
        ]);
        $this->screeningA2 = Screening::create([
            'client_id' => $this->clientA2->id,
            'company_id' => $this->companyA->id,
            'user_id' => $this->operadorA2->id,
            'subject_code' => 'ALFA2',
        ]);

        // 8. Clientes y Estudios de Beta
        $this->clientB1 = Client::create([
            'company_id' => $this->companyB->id,
            'full_name' => 'Paciente Beta 1',
            'phone' => '8133333333',
        ]);
        $this->screeningB1 = Screening::create([
            'client_id' => $this->clientB1->id,
            'company_id' => $this->companyB->id,
            'subject_code' => 'BETA1',
        ]);
    }

    public function test_admin_can_view_all_records(): void
    {
        $this->actingAs($this->admin);

        $clients = ClientResource::getEloquentQuery()->pluck('id');
        $this->assertContains($this->clientA1->id, $clients);
        $this->assertContains($this->clientA2->id, $clients);
        $this->assertContains($this->clientB1->id, $clients);

        $screenings = ScreeningResource::getEloquentQuery()->pluck('id');
        $this->assertContains($this->screeningA1->id, $screenings);
        $this->assertContains($this->screeningA2->id, $screenings);
        $this->assertContains($this->screeningB1->id, $screenings);

        $companies = CompanyResource::getEloquentQuery()->pluck('id');
        $this->assertContains($this->companyA->id, $companies);
        $this->assertContains($this->companyB->id, $companies);

        $users = UserResource::getEloquentQuery()->pluck('id');
        $this->assertContains($this->admin->id, $users);
        $this->assertContains($this->empresarialA->id, $users);
        $this->assertContains($this->operadorA1->id, $users);
    }

    public function test_empresarial_can_view_all_clients_and_screenings_of_their_company(): void
    {
        $this->actingAs($this->empresarialA);

        $clients = ClientResource::getEloquentQuery()->pluck('id');
        $this->assertContains($this->clientA1->id, $clients);
        $this->assertContains($this->clientA2->id, $clients);
        $this->assertNotContains($this->clientB1->id, $clients);

        $screenings = ScreeningResource::getEloquentQuery()->pluck('id');
        $this->assertContains($this->screeningA1->id, $screenings);
        $this->assertContains($this->screeningA2->id, $screenings);
        $this->assertNotContains($this->screeningB1->id, $screenings);
    }

    public function test_empresarial_can_manage_users_of_their_own_company_only(): void
    {
        $this->actingAs($this->empresarialA);

        $users = UserResource::getEloquentQuery()->pluck('id');
        // Debe ver a los usuarios de su empresa (Empresa Alfa)
        $this->assertContains($this->empresarialA->id, $users);
        $this->assertContains($this->operadorA1->id, $users);
        $this->assertContains($this->operadorA2->id, $users);
        // NO debe ver a admin ni usuarios de otras empresas ni independientes
        $this->assertNotContains($this->admin->id, $users);
        $this->assertNotContains($this->independentUser->id, $users);

        $this->assertTrue(UserResource::canViewAny());
        $this->assertTrue(UserResource::canCreate());
    }

    public function test_user_can_only_view_their_own_clients_and_screenings(): void
    {
        $this->actingAs($this->operadorA1);

        $clients = ClientResource::getEloquentQuery()->pluck('id');
        $this->assertContains($this->clientA1->id, $clients);
        $this->assertNotContains($this->clientA2->id, $clients);
        $this->assertNotContains($this->clientB1->id, $clients);

        $screenings = ScreeningResource::getEloquentQuery()->pluck('id');
        $this->assertContains($this->screeningA1->id, $screenings);
        $this->assertNotContains($this->screeningA2->id, $screenings);
        $this->assertNotContains($this->screeningB1->id, $screenings);
    }

    public function test_user_cannot_access_user_or_company_resources(): void
    {
        $this->actingAs($this->operadorA1);

        $this->assertFalse(UserResource::canViewAny());
        $this->assertFalse(UserResource::canCreate());
        $this->assertFalse(CompanyResource::canViewAny());
        $this->assertFalse(CompanyResource::canCreate());
    }

    public function test_models_automatically_bind_auth_user_and_company(): void
    {
        $this->actingAs($this->operadorA1);

        $newClient = Client::create([
            'full_name' => 'Nuevo Colaborador Auto',
        ]);

        $this->assertEquals($this->operadorA1->id, $newClient->user_id);
        $this->assertEquals($this->companyA->id, $newClient->company_id);

        $newScreening = Screening::create([
            'client_id' => $newClient->id,
            'subject_code' => 'AUTO1',
        ]);

        $this->assertEquals($this->operadorA1->id, $newScreening->user_id);
        $this->assertEquals($this->companyA->id, $newScreening->company_id);
    }
}
