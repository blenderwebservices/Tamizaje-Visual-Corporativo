<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('screenings', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('client_id')->constrained('clients')->cascadeOnDelete();
            $table->foreignId('company_id')->nullable()->constrained('companies')->nullOnDelete();
            
            // Metadatos SpotVision
            $table->string('subject_code')->nullable()->index(); // ID Sujeto (ENG7)
            $table->string('barcode_code')->nullable()->index(); // 04490423_IR_ENG7_20260130_112849_0
            $table->dateTime('exam_date')->nullable()->index();
            $table->string('original_pdf_path');
            $table->string('original_filename');
            $table->string('preview_image_path')->nullable();
            $table->string('device_serial')->nullable();
            
            // Diagnóstico general y estado
            $table->string('screening_status')->default('refer')->index(); // 'pass', 'refer', 'incomplete'
            $table->string('status_label')->nullable(); // 'Selección finalizada', 'Todas las mediciones en rangos normales', 'Remitir...'
            $table->boolean('wears_glasses')->default(false);
            $table->decimal('interpupillary_distance_mm', 5, 2)->nullable(); // 70 mm
            $table->string('cylinder_mode', 10)->default('-CIL');
            
            // Ojo Derecho (OD)
            $table->decimal('od_sphere_se', 5, 2)->nullable(); // SE (-0.50)
            $table->decimal('od_sphere_ds', 5, 2)->nullable(); // DS (0.00)
            $table->decimal('od_cylinder_dc', 5, 2)->nullable(); // DC (-0.75)
            $table->smallInteger('od_axis')->nullable(); // @163°
            $table->decimal('od_pupil_size_mm', 4, 2)->nullable(); // 3.8 mm
            $table->string('od_gaze_v', 20)->nullable(); // ↓ 1°
            $table->string('od_gaze_h', 20)->nullable(); // 0°
            
            // Ojo Izquierdo (OS)
            $table->decimal('os_sphere_se', 5, 2)->nullable(); // SE (-0.50)
            $table->decimal('os_sphere_ds', 5, 2)->nullable(); // DS (-0.50)
            $table->decimal('os_cylinder_dc', 5, 2)->nullable(); // DC (-0.25)
            $table->smallInteger('os_axis')->nullable(); // @30°
            $table->decimal('os_pupil_size_mm', 4, 2)->nullable(); // 3.9 mm
            $table->string('os_gaze_v', 20)->nullable(); // ↓ 1°
            $table->string('os_gaze_h', 20)->nullable(); // ← 1°
            
            // Análisis Clínico Rápido y Afecciones Detectadas
            $table->json('findings')->nullable(); // ['miopia', 'astigmatismo', 'anisometropia', ...]
            $table->text('quick_analysis_summary')->nullable(); // Síntesis clínica amigable para el paciente
            $table->text('recommendations')->nullable();
            
            // Información técnica de extracción
            $table->string('extraction_method', 30)->default('programmatic'); // programmatic, ai_vision, manual
            $table->json('raw_extracted_data')->nullable();
            
            // Gestión de Envíos (WhatsApp / Correo)
            $table->string('whatsapp_status', 20)->default('pending')->index(); // pending, sent, failed
            $table->timestamp('whatsapp_sent_at')->nullable();
            $table->text('whatsapp_message_body')->nullable();
            
            $table->string('email_status', 20)->default('pending')->index();
            $table->timestamp('email_sent_at')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('screenings');
    }
};
