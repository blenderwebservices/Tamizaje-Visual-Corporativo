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
        Schema::table('screenings', function (Blueprint $table) {
            // Diámetro Naso Pupilar (DNP) por ojo [18.0 .. 38.5] escala 0.5
            $table->decimal('od_dnp_mm', 4, 1)->nullable()->after('od_gaze_h');
            $table->decimal('os_dnp_mm', 4, 1)->nullable()->after('os_gaze_h');

            // Adición (ADD) por ojo [+0.75 .. +3.50] escala 0.25
            $table->decimal('od_add', 4, 2)->nullable()->after('od_dnp_mm');
            $table->decimal('os_add', 4, 2)->nullable()->after('os_dnp_mm');

            // Datos clínicos y notas del optometrista para el reporte de Graduación
            $table->string('optometrist_name', 150)->nullable()->after('recommendations');
            $table->text('optometrist_notes')->nullable()->after('optometrist_name');

            // Trazabilidad de envíos del Reporte de Graduación
            $table->timestamp('graduation_sent_at')->nullable()->after('whatsapp_sent_at');
            $table->timestamp('graduation_email_sent_at')->nullable()->after('email_sent_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('screenings', function (Blueprint $table) {
            $table->dropColumn([
                'od_dnp_mm',
                'os_dnp_mm',
                'od_add',
                'os_add',
                'optometrist_name',
                'optometrist_notes',
                'graduation_sent_at',
                'graduation_email_sent_at',
            ]);
        });
    }
};
