<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Company;
use App\Models\Screening;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PublicScreeningController extends Controller
{
    /**
     * Fase 1: Muestra el formulario público móvil de captura de datos
     */
    public function showRegistrationForm(Request $request)
    {
        $companies = Company::orderBy('name')->get();
        $selectedCompanyId = $request->query('empresa');

        return view('public.registration', [
            'companies' => $companies,
            'selectedCompanyId' => $selectedCompanyId,
        ]);
    }

    /**
     * Fase 1: Procesa el registro del colaborador con deduplicación y consentimiento legal
     */
    public function submitRegistration(Request $request)
    {
        $validated = $request->validate([
            'company_id' => 'nullable|exists:companies,id',
            'full_name' => 'required|string|min:3|max:200',
            'phone' => 'required|string|min:8|max:20',
            'email' => 'nullable|email|max:150',
            'birth_date' => 'nullable|date',
            'age' => 'nullable|integer|min:1|max:120',
            'gender' => 'nullable|in:H,M,O',
            'terms_accepted' => 'accepted',
        ]);

        $sanitizedPhone = Client::sanitizePhone($validated['phone']);

        // Calcular edad si vino birth_date o viceversa
        $birthDate = $validated['birth_date'] ? Carbon::parse($validated['birth_date']) : null;
        $age = $validated['age'] ?? ($birthDate ? $birthDate->age : null);

        // Deduplicación inteligente: verificar si ya existe este colaborador
        $client = Client::findMatch(
            $validated['full_name'],
            null,
            $birthDate,
            $age
        );

        if (!$client) {
            // Generar clave de sujeto temporal para el operador de SpotVision (ej. SPO-1234)
            $subjectCode = 'SPO' . rand(100, 999);

            $parts = explode(' ', trim($validated['full_name']), 2);
            $client = Client::create([
                'uuid' => (string) Str::uuid(),
                'company_id' => $validated['company_id'] ?? null,
                'client_code' => 'CLI-' . strtoupper(Str::random(6)),
                'subject_code' => $subjectCode,
                'first_name' => $parts[0] ?? '',
                'last_name' => $parts[1] ?? '',
                'full_name' => trim($validated['full_name']),
                'phone' => $sanitizedPhone,
                'email' => $validated['email'] ?? null,
                'birth_date' => $birthDate ? $birthDate->toDateString() : null,
                'age' => $age,
                'gender' => $validated['gender'] ?? null,
                'terms_accepted' => true,
                'terms_accepted_at' => now(),
                'crm_stage' => 'prospect',
            ]);
        } else {
            // Actualizar datos de contacto si vinieron en el formulario
            $client->update([
                'phone' => $sanitizedPhone ?: $client->phone,
                'email' => $validated['email'] ?: $client->email,
                'company_id' => $validated['company_id'] ?: $client->company_id,
                'terms_accepted' => true,
                'terms_accepted_at' => now(),
            ]);
        }

        return redirect()->route('registration.pass', ['uuid' => $client->uuid]);
    }

    /**
     * Muestra el pase digital y QR con el ID de Sujeto para la mesa de SpotVision
     */
    public function showRegistrationPass(string $uuid)
    {
        $client = Client::with('company')->where('uuid', $uuid)->firstOrFail();
        return view('public.registration-pass', ['client' => $client]);
    }

    /**
     * Fase 3: Muestra el reporte digital amigable para el colaborador/paciente
     */
    public function showDigitalReport(string $uuid)
    {
        $screening = Screening::with(['client', 'company'])
            ->where('uuid', $uuid)
            ->firstOrFail();

        return view('public.digital-report', [
            'screening' => $screening,
            'client' => $screening->client,
            'company' => $screening->company,
        ]);
    }

    /**
     * Descarga segura del archivo PDF original emitido por el SpotVision
     */
    public function downloadPdf(string $uuid)
    {
        $screening = Screening::where('uuid', $uuid)->firstOrFail();
        $filePath = storage_path('app/' . $screening->original_pdf_path);

        if (!file_exists($filePath)) {
            abort(404, 'Archivo original no encontrado en el servidor.');
        }

        return response()->download($filePath, $screening->original_filename, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . addslashes($screening->original_filename) . '"',
        ]);
    }
}
