<?php

namespace App\Services;

class SpotVisionClinicalAnalyzer
{
    /**
     * Evalúa los valores optométricos y genera un análisis clínico rápido
     */
    public function analyze(array $data): array
    {
        $findings = [];
        $isRefer = false;

        $odSe = isset($data['od_sphere_se']) ? (float)$data['od_sphere_se'] : null;
        $osSe = isset($data['os_sphere_se']) ? (float)$data['os_sphere_se'] : null;
        $odDc = isset($data['od_cylinder_dc']) ? (float)$data['od_cylinder_dc'] : null;
        $osDc = isset($data['os_cylinder_dc']) ? (float)$data['os_cylinder_dc'] : null;
        $odDs = isset($data['od_sphere_ds']) ? (float)$data['od_sphere_ds'] : null;
        $osDs = isset($data['os_sphere_ds']) ? (float)$data['os_sphere_ds'] : null;
        $odPupil = isset($data['od_pupil_size_mm']) ? (float)$data['od_pupil_size_mm'] : null;
        $osPupil = isset($data['os_pupil_size_mm']) ? (float)$data['os_pupil_size_mm'] : null;

        // 1. Astigmatismo (Cilindro <= -0.75D)
        if (($odDc !== null && $odDc <= -0.75) || ($osDc !== null && $osDc <= -0.75)) {
            $findings[] = 'Astigmatismo';
            $isRefer = true;
        }

        // 2. Miopía (Esfera equivalente o DS <= -0.75D)
        if (($odSe !== null && $odSe <= -0.75) || ($osSe !== null && $osSe <= -0.75) ||
            ($odDs !== null && $odDs <= -0.75) || ($osDs !== null && $osDs <= -0.75)) {
            $findings[] = 'Miopía';
            $isRefer = true;
        }

        // 3. Hipermetropía (Esfera >= +1.00D)
        if (($odSe !== null && $odSe >= 1.00) || ($osSe !== null && $osSe >= 1.00)) {
            $findings[] = 'Hipermetropía';
            $isRefer = true;
        }

        // 4. Anisometropía (Diferencia de potencia refractiva >= 1.00D entre ojos)
        if ($odSe !== null && $osSe !== null && abs($odSe - $osSe) >= 1.00) {
            $findings[] = 'Anisometropía';
            $isRefer = true;
        }

        // 5. Anisocoria (Diferencia pupilar >= 1.0 mm)
        if ($odPupil !== null && $osPupil !== null && abs($odPupil - $osPupil) >= 1.0) {
            $findings[] = 'Anisocoria';
            $isRefer = true;
        }

        // Determinar status general
        $status = $isRefer ? 'refer' : 'pass';
        if (isset($data['overall_status'])) {
            $rawStatus = strtoupper(trim($data['overall_status']));
            if ($rawStatus === 'PASA' || $rawStatus === 'PASS') {
                // Si el reporte explícitamente marcó PASA pero hay leves valores, podemos respetar o anotar
                if (empty($findings)) {
                    $status = 'pass';
                }
            }
        }

        // Construir Síntesis Amigable en Lenguaje Humano
        $summaryParts = [];
        if (empty($findings)) {
            $summaryParts[] = 'Tus ojos presentan una agudeza visual y alineación dentro de rangos normales de cribado.';
            $recommendations = 'Mantén tus hábitos de salud visual con pausas activas cada 20 minutos frente a pantallas e iluminación adecuada. Se recomienda un examen preventivo anual.';
        } else {
            $conditionsText = implode(' y ', $findings);
            $summaryParts[] = "Se detectó presencia de {$conditionsText}.";

            if ($odDc !== null && $odDc <= -0.75) {
                $axisText = isset($data['od_axis']) ? " a {$data['od_axis']}°" : '';
                $summaryParts[] = "En ojo derecho se observa astigmatismo ({$odDc} DC{$axisText}).";
            }
            if ($osDs !== null && $osDs <= -0.50) {
                $summaryParts[] = "En ojo izquierdo se observa leve compensación esférica ({$osDs} DS).";
            }

            $recommendations = 'Se recomienda acudir a una refracción clínica completa con nuestro optometrista para graduar armazón oftálmico o lentes de seguridad con protección de luz azul (Blue Defense) y antirreflejante.';
        }

        $summary = implode(' ', $summaryParts);

        return [
            'screening_status' => $status,
            'findings' => array_values(array_unique($findings)),
            'quick_analysis_summary' => $summary,
            'recommendations' => $recommendations,
        ];
    }
}
