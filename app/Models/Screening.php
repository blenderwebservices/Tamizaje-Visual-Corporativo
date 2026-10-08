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
        'od_dnp_mm',
        'os_dnp_mm',
        'od_add',
        'os_add',
        'optometrist_name',
        'optometrist_notes',
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
        'graduation_sent_at',
        'graduation_email_sent_at',
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
        'od_dnp_mm' => 'decimal:1',
        'os_dnp_mm' => 'decimal:1',
        'od_add' => 'decimal:2',
        'os_add' => 'decimal:2',
        'findings' => 'array',
        'raw_extracted_data' => 'array',
        'whatsapp_sent_at' => 'datetime',
        'email_sent_at' => 'datetime',
        'graduation_sent_at' => 'datetime',
        'graduation_email_sent_at' => 'datetime',
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

    /**
     * Enlace público al reporte de Graduación / Receta Óptica
     */
    public function getGraduationReportUrlAttribute(): string
    {
        return url('/graduacion/' . $this->uuid);
    }

    /**
     * Genera el texto formal y clínico para el envío de Graduación por WhatsApp
     */
    public function generateGraduationWhatsAppMessage(): string
    {
        $name = $this->client ? $this->client->first_name ?? $this->client->full_name : 'Estimado(a) paciente';
        $companyName = $this->company ? $this->company->name : 'su jornada visual';
        $optometrist = $this->optometrist_name ?: 'Enjoy Vision';
        $gradUrl = $this->graduation_report_url;

        // Formateo de Refracción OD
        $odSph = $this->od_sphere_ds !== null ? sprintf('%+.2f', $this->od_sphere_ds) : ($this->od_sphere_se !== null ? sprintf('%+.2f', $this->od_sphere_se) : 'N/A');
        $odCyl = $this->od_cylinder_dc !== null ? sprintf('%.2f', $this->od_cylinder_dc) : 'N/A';
        $odAxis = $this->od_axis !== null ? "{$this->od_axis}°" : 'N/A';
        $odDnp = $this->od_dnp_mm !== null ? "{$this->od_dnp_mm} mm" : 'N/A';
        $odAdd = $this->od_add !== null ? sprintf('+%.2f', $this->od_add) : null;

        // Formateo de Refracción OS
        $osSph = $this->os_sphere_ds !== null ? sprintf('%+.2f', $this->os_sphere_ds) : ($this->os_sphere_se !== null ? sprintf('%+.2f', $this->os_sphere_se) : 'N/A');
        $osCyl = $this->os_cylinder_dc !== null ? sprintf('%.2f', $this->os_cylinder_dc) : 'N/A';
        $osAxis = $this->os_axis !== null ? "{$this->os_axis}°" : 'N/A';
        $osDnp = $this->os_dnp_mm !== null ? "{$this->os_dnp_mm} mm" : 'N/A';
        $osAdd = $this->os_add !== null ? sprintf('+%.2f', $this->os_add) : null;

        $msg = "👓 *REPORTE OFICIAL DE GRADUACIÓN VISUAL* 👓\n";
        $msg .= "*Enjoy Vision • Salud Visual Corporativa*\n\n";
        $msg .= "Hola {$name}! 👋\n";
        $msg .= "Te compartimos tu prescripción óptica y graduación clínica confirmada en {$companyName}.\n\n";

        $msg .= "📋 *Fórmula Óptica:*\n";
        $msg .= "🔹 *Ojo Derecho (OD):*\n";
        $msg .= "   • Esfera: {$odSph} | Cilindro: {$odCyl} | Eje: {$odAxis}\n";
        $msg .= "   • DNP: {$odDnp}" . ($odAdd ? " | ADD: {$odAdd}" : "") . "\n\n";

        $msg .= "🔹 *Ojo Izquierdo (OS):*\n";
        $msg .= "   • Esfera: {$osSph} | Cilindro: {$osCyl} | Eje: {$osAxis}\n";
        $msg .= "   • DNP: {$osDnp}" . ($osAdd ? " | ADD: {$osAdd}" : "") . "\n\n";

        if ($this->interpupillary_distance_mm) {
            $msg .= "📏 *Distancia Interpupilar (DIP):* {$this->interpupillary_distance_mm} mm\n\n";
        }

        if (!empty($this->optometrist_notes)) {
            $msg .= "📝 *Notas del Especialista:*\n{$this->optometrist_notes}\n\n";
        }

        $msg .= "🌐 *Tu Receta y Reporte Digital Completo:*\n{$gradUrl}\n\n";
        $msg .= "Atentamente,\n*Equipo Clínico Enjoy Vision* 👁️✨";

        return $msg;
    }

    /**
     * Genera la URL de WhatsApp para el Reporte de Graduación
     */
    public function getGraduationWhatsAppUrlAttribute(): ?string
    {
        $phone = $this->client ? $this->client->phone : null;
        if (empty($phone)) {
            return null;
        }

        $cleanPhone = Client::sanitizePhone($phone);
        if (empty($cleanPhone)) {
            return null;
        }

        $message = $this->generateGraduationWhatsAppMessage();
        return 'https://api.whatsapp.com/send?phone=' . urlencode($cleanPhone) . '&text=' . urlencode($message);
    }
}
