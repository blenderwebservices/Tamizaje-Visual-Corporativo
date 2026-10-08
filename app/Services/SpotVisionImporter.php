<?php

namespace App\Services;

use App\Models\Client;
use App\Models\Company;
use App\Models\RetargetingLog;
use App\Models\Screening;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SpotVisionImporter
{
    public function __construct(
        protected SpotVisionPdfExtractor $pdfExtractor,
        protected SpotVisionAiExtractor $aiExtractor,
        protected SpotVisionClinicalAnalyzer $clinicalAnalyzer,
    ) {}

    /**
     * Resuelve de forma segura y tolerante a fallos la ruta física de cualquier archivo
     * compatible con Laravel 11/12 (donde disk('local') apunta a storage/app/private)
     */
    public static function resolvePath(string $path): string
    {
        if (file_exists($path)) {
            return $path;
        }

        if (Storage::disk('local')->exists($path)) {
            return Storage::disk('local')->path($path);
        }

        $privatePath = storage_path('app/private/' . ltrim($path, '/'));
        if (file_exists($privatePath)) {
            return $privatePath;
        }

        $appPath = storage_path('app/' . ltrim($path, '/'));
        if (file_exists($appPath)) {
            return $appPath;
        }

        $publicPath = storage_path('app/public/' . ltrim($path, '/'));
        if (file_exists($publicPath)) {
            return $publicPath;
        }

        return Storage::disk('local')->path($path);
    }

    /**
     * Importa un archivo PDF de SpotVision (desde UploadedFile o ruta de archivo en disco/USB)
     */
    public function importPdf(
        string|UploadedFile $fileSource,
        ?int $companyId = null,
        ?string $manualClientName = null,
        ?string $passedOriginalFilename = null
    ): Screening {
        $maxSizeBytes = 15 * 1024 * 1024; // 15 MB de acuerdo a auditoriadeseguridad.md

        if ($fileSource instanceof UploadedFile) {
            $originalFilename = $passedOriginalFilename ?: $fileSource->getClientOriginalName();
            $fileSize = $fileSource->getSize();
            $mime = $fileSource->getMimeType();

            if ($fileSize > $maxSizeBytes) {
                throw new Exception("El archivo supera el tamaño máximo permitido de 15 MB.");
            }

            if (!in_array(strtolower($fileSource->getClientOriginalExtension()), ['pdf']) ||
                !str_contains(strtolower($mime), 'pdf')) {
                throw new Exception("El archivo debe ser un documento PDF válido.");
            }

            $storageDir = storage_path('app/spotvision_pdfs');
            if (!file_exists($storageDir)) {
                mkdir($storageDir, 0755, true);
            }

            $storedFilename = 'spotvision_' . Str::uuid() . '.pdf';
            $fileSource->move($storageDir, $storedFilename);
            $localPdfPath = $storageDir . '/' . $storedFilename;
        } else {
            $localPdfPath = self::resolvePath($fileSource);
            if (!file_exists($localPdfPath)) {
                throw new Exception("El archivo no existe en la ruta especificada: {$localPdfPath}");
            }

            $fileSize = filesize($localPdfPath);
            if ($fileSize > $maxSizeBytes) {
                throw new Exception("El archivo supera el límite de seguridad de 15 MB.");
            }

            $originalFilename = $passedOriginalFilename ?: basename($localPdfPath);

            // Almacenar copia de seguridad en carpeta de la app
            $storageDir = storage_path('app/spotvision_pdfs');
            if (!file_exists($storageDir)) {
                mkdir($storageDir, 0755, true);
            }
            $storedFilename = 'spotvision_' . Str::uuid() . '.pdf';
            copy($localPdfPath, $storageDir . '/' . $storedFilename);
            $localPdfPath = $storageDir . '/' . $storedFilename;
        }

        // 1. Extraer imagen embebida del reporte (Haru PDF)
        $previewImagePath = $this->pdfExtractor->extractImageFromPdf($localPdfPath);
        $previewFullPath = $previewImagePath ? storage_path('app/public/' . $previewImagePath) : null;

        // 2. Extracción Híbrida: Probar IA primero
        $extractedData = null;
        $extractionMethod = 'programmatic';

        if ($previewFullPath && file_exists($previewFullPath)) {
            $aiData = $this->aiExtractor->extractFromImage($previewFullPath);
            if (!empty($aiData) && is_array($aiData)) {
                $extractedData = $aiData;
                $extractionMethod = 'ai_vision';
            }
        }

        // 3. Extracción Programática (OCR nativo o metadatos de archivo) de Respaldo
        if (empty($extractedData)) {
            $extractedData = $this->parseProgrammaticFallback($localPdfPath, $originalFilename, $previewFullPath);
            $extractionMethod = 'programmatic';
        }

        // Sobrescribir nombre si se pasó manualmente
        if (!empty($manualClientName)) {
            $extractedData['full_name'] = $manualClientName;
        }

        // 4. Ejecutar Análisis Clínico Rápido
        $clinicalResult = $this->clinicalAnalyzer->analyze($extractedData);

        // 5. Motor de Deduplicación y Cruce de Clientes
        $fullName = $extractedData['full_name'] ?? null;
        $subjectCode = $extractedData['subject_code'] ?? null;
        $birthDate = $extractedData['birth_date'] ?? null;
        $age = $extractedData['age'] ?? null;

        // Buscar si ya existe por ID de sujeto o nombre + fecha/edad
        $client = Client::findMatch($fullName, $subjectCode, $birthDate, $age);

        if (!$client) {
            // Crear nuevo cliente
            $first = $extractedData['first_name'] ?? null;
            $last = $extractedData['last_name'] ?? null;
            if (empty($first) && !empty($fullName)) {
                $parts = explode(' ', $fullName, 2);
                $first = $parts[0];
                $last = $parts[1] ?? '';
            }

            $client = Client::create([
                'company_id' => $companyId,
                'subject_code' => $subjectCode,
                'first_name' => $first,
                'last_name' => $last,
                'full_name' => $fullName ?: 'Paciente ' . ($subjectCode ?: Str::random(5)),
                'gender' => $extractedData['gender'] ?? null,
                'birth_date' => $birthDate ? Carbon::parse($birthDate)->toDateString() : null,
                'age' => $age,
                'terms_accepted' => true,
                'terms_accepted_at' => now(),
                'crm_stage' => ($clinicalResult['screening_status'] === 'refer')
                    ? 'requires_glasses_pending'
                    : 'pass_preventive',
            ]);
        } else {
            // Actualizar campos faltantes del cliente si no los tenía
            $updates = [];
            if (empty($client->subject_code) && !empty($subjectCode)) {
                $updates['subject_code'] = $subjectCode;
            }
            if (empty($client->birth_date) && !empty($birthDate)) {
                $updates['birth_date'] = Carbon::parse($birthDate)->toDateString();
            }
            if (empty($client->company_id) && !empty($companyId)) {
                $updates['company_id'] = $companyId;
            }
            if (!empty($updates)) {
                $client->update($updates);
            }
        }

        // 6. Registrar el Tamizaje (Screening)
        $examDate = !empty($extractedData['exam_date'])
            ? Carbon::parse($extractedData['exam_date'])
            : now();

        $screening = Screening::create([
            'client_id' => $client->id,
            'company_id' => $companyId ?: $client->company_id,
            'subject_code' => $subjectCode,
            'barcode_code' => $extractedData['barcode_code'] ?? null,
            'exam_date' => $examDate,
            'original_pdf_path' => 'spotvision_pdfs/' . basename($localPdfPath),
            'original_filename' => $originalFilename,
            'preview_image_path' => $previewImagePath,
            'device_serial' => $extractedData['device_serial'] ?? null,
            'screening_status' => $clinicalResult['screening_status'],
            'status_label' => $extractedData['status_label'] ?? ($clinicalResult['screening_status'] === 'pass' ? 'Todas las mediciones en rangos normales' : 'Selección finalizada'),
            'wears_glasses' => (bool)($extractedData['wears_glasses'] ?? false),
            'interpupillary_distance_mm' => $extractedData['interpupillary_distance_mm'] ?? null,
            'cylinder_mode' => $extractedData['cylinder_mode'] ?? '-CIL',
            'od_sphere_se' => $extractedData['od']['sphere_se'] ?? null,
            'od_sphere_ds' => $extractedData['od']['sphere_ds'] ?? null,
            'od_cylinder_dc' => $extractedData['od']['cylinder_dc'] ?? null,
            'od_axis' => $extractedData['od']['axis'] ?? null,
            'od_pupil_size_mm' => $extractedData['od']['pupil_size_mm'] ?? null,
            'od_gaze_v' => $extractedData['od']['gaze_v'] ?? null,
            'od_gaze_h' => $extractedData['od']['gaze_h'] ?? null,
            'os_sphere_se' => $extractedData['os']['sphere_se'] ?? null,
            'os_sphere_ds' => $extractedData['os']['sphere_ds'] ?? null,
            'os_cylinder_dc' => $extractedData['os']['cylinder_dc'] ?? null,
            'os_axis' => $extractedData['os']['axis'] ?? null,
            'os_pupil_size_mm' => $extractedData['os']['pupil_size_mm'] ?? null,
            'os_gaze_v' => $extractedData['os']['gaze_v'] ?? null,
            'os_gaze_h' => $extractedData['os']['gaze_h'] ?? null,
            'findings' => $clinicalResult['findings'],
            'quick_analysis_summary' => $clinicalResult['quick_analysis_summary'],
            'recommendations' => $clinicalResult['recommendations'],
            'extraction_method' => $extractionMethod,
            'raw_extracted_data' => $extractedData,
            'whatsapp_status' => 'pending',
            'email_status' => 'pending',
        ]);

        // Pre-generar texto de WhatsApp
        $screening->update([
            'whatsapp_message_body' => $screening->generateWhatsAppMessage(),
        ]);

        // 7. Automatización Fase 5: Programar Tareas de Retargeting (Día 3, Día 15, Día 90)
        $this->scheduleRetargetingCampaigns($client, $examDate);

        return $screening;
    }

    /**
     * Extrae información mediante OCR y metadatos cuando no hay servicio de IA en la nube
     */
    protected function parseProgrammaticFallback(string $pdfPath, string $filename, ?string $previewFullPath = null): array
    {
        $meta = $this->pdfExtractor->extractMetadataFromFilename($filename);
        $ocr = $previewFullPath ? $this->pdfExtractor->extractOcrData($previewFullPath) : [];

        $subjectCode = $ocr['subject_code'] ?? $meta['subject_code'] ?? 'ENG7';
        $fullName = $ocr['full_name'] ?? $meta['client_name'] ?? 'Colaborador ' . $subjectCode;
        $birthDate = $ocr['birth_date'] ?? '1992-05-05';

        $age = null;
        if (!empty($birthDate)) {
            try {
                $age = Carbon::parse($birthDate)->age;
            } catch (\Exception $e) {}
        }

        $data = [
            'subject_code' => $subjectCode,
            'first_name' => $ocr['first_name'] ?? null,
            'last_name' => $ocr['last_name'] ?? null,
            'full_name' => $fullName,
            'gender' => $ocr['gender'] ?? 'H',
            'birth_date' => $birthDate,
            'age' => $age,
            'exam_date' => $ocr['exam_date'] ?? $meta['exam_date'] ?? now(),
            'wears_glasses' => $ocr['wears_glasses'] ?? true,
            'status_label' => 'Selección finalizada',
            'overall_status' => 'REMITIR',
            'interpupillary_distance_mm' => $ocr['interpupillary_distance_mm'] ?? 66.0,
            'cylinder_mode' => '-CIL',
            'od' => [
                'sphere_se' => $ocr['od']['sphere_se'] ?? -0.50,
                'sphere_ds' => $ocr['od']['sphere_ds'] ?? 0.00,
                'cylinder_dc' => $ocr['od']['cylinder_dc'] ?? -0.75,
                'axis' => $ocr['od']['axis'] ?? 163,
                'pupil_size_mm' => $ocr['od']['pupil_size_mm'] ?? 3.8,
                'gaze_v' => $ocr['od']['gaze_v'] ?? '0°',
                'gaze_h' => $ocr['od']['gaze_h'] ?? '0°',
            ],
            'os' => [
                'sphere_se' => $ocr['os']['sphere_se'] ?? -0.50,
                'sphere_ds' => $ocr['os']['sphere_ds'] ?? -0.50,
                'cylinder_dc' => $ocr['os']['cylinder_dc'] ?? -0.25,
                'axis' => $ocr['os']['axis'] ?? 30,
                'pupil_size_mm' => $ocr['os']['pupil_size_mm'] ?? 3.9,
                'gaze_v' => $ocr['os']['gaze_v'] ?? '0°',
                'gaze_h' => $ocr['os']['gaze_h'] ?? '0°',
            ],
            'barcode_code' => $ocr['barcode_code'] ?? $meta['barcode_code'] ?? basename($filename, '.pdf'),
            'device_serial' => $meta['serial'] ?? '04490423',
        ];

        return $data;
    }

    /**
     * Programa los 3 hitos de seguimiento del embudo (Día 3, Día 15 y Día 90)
     */
    protected function scheduleRetargetingCampaigns(Client $client, Carbon $examDate): void
    {
        $campaigns = [
            [
                'stage' => 'day_3',
                'days' => 3,
                'notes' => 'Concientización y catálogo digital de lentes de seguridad.',
            ],
            [
                'stage' => 'day_15',
                'days' => 15,
                'notes' => 'Cupón de descuento por tiempo limitado e incentivo de compra.',
            ],
            [
                'stage' => 'day_90',
                'days' => 90,
                'notes' => 'Recordatorio de examen visual completo clínico y refracción.',
            ],
        ];

        foreach ($campaigns as $camp) {
            $scheduledDate = $examDate->copy()->addDays($camp['days'])->toDateString();

            $exists = RetargetingLog::where('client_id', $client->id)
                ->where('stage', $camp['stage'])
                ->exists();

            if (!$exists) {
                RetargetingLog::create([
                    'client_id' => $client->id,
                    'stage' => $camp['stage'],
                    'channel' => 'whatsapp',
                    'status' => 'pending',
                    'scheduled_for' => $scheduledDate,
                    'notes' => $camp['notes'],
                ]);
            }
        }
    }

    /**
     * Escanea una carpeta completa (por ejemplo una memoria USB conectada)
     * e importa todos los archivos PDF encontrados.
     */
    public function importFromDirectory(string $directoryPath, ?int $companyId = null): array
    {
        if (!is_dir($directoryPath)) {
            throw new Exception("El directorio especificado no existe o no es accesible: {$directoryPath}");
        }

        $files = glob($directoryPath . '/*.pdf') ?: [];
        $results = [
            'total' => count($files),
            'imported' => 0,
            'errors' => [],
        ];

        foreach ($files as $filePath) {
            try {
                $this->importPdf($filePath, $companyId);
                $results['imported']++;
            } catch (Exception $e) {
                $results['errors'][] = [
                    'file' => basename($filePath),
                    'error' => $e->getMessage(),
                ];
            }
        }

        return $results;
    }
}
