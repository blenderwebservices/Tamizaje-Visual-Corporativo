<?php

namespace Tests\Feature;

use App\Filament\Resources\ScreeningResource\Pages\CreateScreening;
use App\Models\Client;
use App\Models\Company;
use App\Models\Screening;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SpotVisionFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $company = Company::create([
            'name' => 'Schneider Electric',
            'location' => 'Apodaca',
            'event_date' => '2026-01-30',
        ]);

        $client = Client::create([
            'company_id' => $company->id,
            'full_name' => 'Izamar Rodriguez Cabello',
            'first_name' => 'Izamar',
            'last_name' => 'Rodriguez Cabello',
            'phone' => '528114567890',
            'birth_date' => '1992-05-05',
            'age' => 33,
            'gender' => 'H',
            'terms_accepted' => true,
        ]);

        Screening::create([
            'client_id' => $client->id,
            'company_id' => $company->id,
            'subject_code' => 'ENG7',
            'exam_date' => now(),
            'original_pdf_path' => 'spotvision_pdfs/test.pdf',
            'original_filename' => 'Izamar Rodriguez Cabello.pdf',
            'screening_status' => 'pass',
            'od_sphere_se' => -0.50,
            'od_cylinder_dc' => -0.75,
            'od_axis' => 163,
            'os_sphere_se' => -0.50,
            'os_cylinder_dc' => -0.25,
            'os_axis' => 30,
            'quick_analysis_summary' => 'Valores de tamizaje dentro de rango normal.',
        ]);
    }

    public function test_landing_page_renders_successfully(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Flujo de Tamizaje Visual Corporativo');
    }

    public function test_intake_registration_form_renders(): void
    {
        $response = $this->get('/registro');
        $response->assertStatus(200);
        $response->assertSee('Escaneo rápido en 10s');
    }

    public function test_intake_registration_submits_and_creates_client(): void
    {
        $company = Company::first();

        $response = $this->post('/registro', [
            'company_id' => $company->id,
            'full_name' => 'Alejandro Morales Treviño',
            'phone' => '8119876543',
            'email' => 'amorales@empresa.com',
            'birth_date' => '1990-03-15',
            'gender' => 'H',
            'terms_accepted' => '1',
        ]);

        $response->assertStatus(302);

        $client = Client::where('full_name', 'Alejandro Morales Treviño')->first();
        $this->assertNotNull($client);
        $this->assertEquals('528119876543', $client->phone);
        $this->assertEquals($company->id, $client->company_id);
    }

    public function test_digital_report_renders_with_clinical_data(): void
    {
        $screening = Screening::first();
        $this->assertNotNull($screening);

        $response = $this->get('/reporte/'.$screening->uuid);
        $response->assertStatus(200);
        $response->assertSee($screening->client->full_name);
        $response->assertSee('Ojo Derecho (OD)');
        $response->assertSee('Ojo Izquierdo (OS)');
    }

    public function test_deduplication_discriminates_duplicate_names_by_birth_date(): void
    {
        $c1 = Client::create([
            'full_name' => 'Juan Perez Garcia',
            'first_name' => 'Juan',
            'last_name' => 'Perez Garcia',
            'birth_date' => '1985-05-10',
            'age' => 40,
            'phone' => '528111111111',
        ]);

        $c2 = Client::create([
            'full_name' => 'Juan Perez Garcia',
            'first_name' => 'Juan',
            'last_name' => 'Perez Garcia',
            'birth_date' => '2001-12-20',
            'age' => 24,
            'phone' => '528122222222',
        ]);

        // Buscar al de 1985
        $found1 = Client::findMatch('Juan Perez Garcia', null, '1985-05-10', 40);
        $this->assertEquals($c1->id, $found1->id);

        // Buscar al de 2001
        $found2 = Client::findMatch('Juan Perez Garcia', null, '2001-12-20', 24);
        $this->assertEquals($c2->id, $found2->id);
    }

    public function test_admin_create_screening_page_renders_successfully(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/admin/screenings/create');
        $response->assertStatus(200);
    }

    public function test_can_create_manual_screening(): void
    {
        $user = User::factory()->create();
        $client = Client::first();

        Livewire::actingAs($user)
            ->test(CreateScreening::class)
            ->fillForm([
                'client_id' => $client->id,
                'screening_status' => 'refer',
                'od_sphere_se' => -1.25,
                'os_sphere_se' => -1.50,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $manualScreening = Screening::where('extraction_method', 'manual')->first();
        $this->assertNotNull($manualScreening);
        $this->assertEquals($client->id, $manualScreening->client_id);
        $this->assertEquals('refer', $manualScreening->screening_status);
        $this->assertStringStartsWith('MANUAL_', $manualScreening->barcode_code);
        $this->assertNotEmpty($manualScreening->whatsapp_message_body);
    }
}
