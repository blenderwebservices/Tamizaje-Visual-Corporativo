<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Screening extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'client_id',
        'company_id',
        'subject_code',
        'barcode_code',
        'exam_date',
        'original_pdf_path',
        'original_filename',
        'preview_image_path',
        'device_serial',
        'screening_status',
        'status_label',
        'wears_glasses',
        'interpupillary_distance_mm',
        'cylinder_mode',
        'od_sphere_se',
        'od_sphere_ds',
        'od_cylinder_dc',
        'od_axis',
        'od_pupil_size_mm',
        'od_gaze_v',
        'od_gaze_h',
        'os_sphere_se',
        'os_sphere_ds',
        'os_cylinder_dc',
        'os_axis',
        'os_pupil_size_mm',
        'os_gaze_v',
        'os_gaze_h',
        'findings',
        'quick_analysis_summary',
        'recommendations',
        'extraction_method',
        'raw_extracted_data',
        'whatsapp_status',
        'whatsapp_sent_at',
        'whatsapp_message_body',
        'email_status',
        'email_sent_at',
    ];

    protected $casts = [
        'exam_date' => 'datetime',
        'wears_glasses' => 'boolean',
        'interpupillary_distance_mm' => 'decimal:2',
        'od_sphere_se' => 'decimal:2',
        'od_sphere_ds' => 'decimal:2',
        'od_cylinder_dc' => 'decimal:2',
        'od_pupil_size_mm' => 'decimal:2',
        'os_sphere_se' => 'decimal:2',
        'os_sphere_ds' => 'decimal:2',
        'os_cylinder_dc' => 'decimal:2',
        'os_pupil_size_mm' => 'decimal:2',
        'findings' => 'array',
        'raw_extracted_data' => 'array',
        'whatsapp_sent_at' => 'datetime',
        'email_sent_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Screening $screening) {
            if (empty($screening->uuid)) {
                $screening->uuid = (string) Str::uuid();
            }
        });
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function getPublicReportUrlAttribute(): string
    {
        return url('/reporte/' . $this->uuid);
    }

    /**
     * Construye el texto amigable para el envío de WhatsApp
     */
    public function generateWhatsAppMessage(): string
    {
        $name = $this->client ? $this->client->first_name ?? $this->client->full_name : 'Estimado(a) paciente';
        $companyName = $this->company ? $this->company->name : 'su jornada de salud visual';
        $statusText = $this->screening_status === 'pass'
            ? '✅ Todas tus mediciones se encuentran dentro de rangos normales.'
            : '⚠️ Se detectaron oportunidades de corrección visual que ameritan valoración clínica.';

        $summary = $this->quick_analysis_summary ?: 'Te invitamos a revisar tu reporte detallado.';
        $reportUrl = $this->public_report_url;

        $msg = "Hola {$name}! 👋\n\n";
        $msg .= "Te compartimos los resultados de tu tamizaje visual computarizado realizado con Spot™ Vision Screener en {$companyName}.\n\n";
        $msg .= "📌 *Resultado General:*\n{$statusText}\n\n";
        $msg .= "🔍 *Análisis Rápido:*\n{$summary}\n\n";
        $msg .= "📄 *Tu Reporte Digital Oficial:*\n{$reportUrl}\n\n";
        $msg .= "Recuerda que este tamizaje preventivo no reemplaza un examen de refracción completo. ¡Cuidar tus ojos mejora tu calidad de vida y rendimiento laboral!";

        return $msg;
    }

    /**
     * Genera la URL directa de WhatsApp Web / Móvil sanitizada
     */
    public function getWhatsAppUrlAttribute(): ?string
    {
        $phone = $this->client ? $this->client->phone : null;
        if (empty($phone)) {
            return null;
        }

        $cleanPhone = Client::sanitizePhone($phone);
        if (empty($cleanPhone)) {
            return null;
        }

        $message = $this->whatsapp_message_body ?: $this->generateWhatsAppMessage();
        return 'https://api.whatsapp.com/send?phone=' . urlencode($cleanPhone) . '&text=' . urlencode($message);
    }
}
