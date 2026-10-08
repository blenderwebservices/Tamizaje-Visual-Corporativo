<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SpotVisionPdfExtractor
{
    /**
     * Extrae la imagen embebida en el PDF de Spot Vision (Haru Free PDF Library)
     * y genera una imagen PNG para previsualización y procesamiento de OCR / IA.
     */
    public function extractImageFromPdf(string $pdfPath): ?string
    {
        if (!file_exists($pdfPath)) {
            return null;
        }

        $content = file_get_contents($pdfPath);
        if (!$content) {
            return null;
        }

        // Buscar el stream de imagen RGB descompreso con FlateDecode
        // /Length 152631 /Type /XObject /Subtype /Image ... /Width 1275 /Height 1650
        $pattern = '/\/Width\s+(\d+)\s*\/Height\s+(\d+)[^\>]*\>\>\s*stream[\r\n]+(.*?)[\r\n]+endstream/s';
        if (!preg_match($pattern, $content, $matches)) {
            // Intentar patrón inverso (Height antes de Width)
            $patternAlt = '/\/Height\s+(\d+)\s*\/Width\s+(\d+)[^\>]*\>\>\s*stream[\r\n]+(.*?)[\r\n]+endstream/s';
            if (preg_match($patternAlt, $content, $matchesAlt)) {
                $w = (int)$matchesAlt[2];
                $h = (int)$matchesAlt[1];
                $rawStream = $matchesAlt[3];
            } else {
                return null;
            }
        } else {
            $w = (int)$matches[1];
            $h = (int)$matches[2];
            $rawStream = $matches[3];
        }

        try {
            $decomp = @gzuncompress($rawStream);
            if (!$decomp) {
                return null;
            }

            // Crear imagen GD
            $img = imagecreatetruecolor($w, $h);
            $idx = 0;
            $len = strlen($decomp);

            // Rasterizado RGB
            for ($y = 0; $y < $h; $y++) {
                for ($x = 0; $x < $w; $x++) {
                    if ($idx + 2 >= $len) break;
                    $r = ord($decomp[$idx++]);
                    $g = ord($decomp[$idx++]);
                    $b = ord($decomp[$idx++]);
                    $color = imagecolorallocate($img, $r, $g, $b);
                    imagesetpixel($img, $x, $y, $color);
                }
            }

            $previewDir = storage_path('app/public/previews');
            if (!file_exists($previewDir)) {
                mkdir($previewDir, 0755, true);
            }

            $previewFilename = 'spotvision_' . Str::random(20) . '.png';
            $previewPath = $previewDir . '/' . $previewFilename;
            imagepng($img, $previewPath);
            imagedestroy($img);

            return 'previews/' . $previewFilename;
        } catch (Exception $e) {
            report($e);
            return null;
        }
    }

    /**
     * Parsea metadatos a partir del nombre del archivo y patrones del reporte Spot Vision
     */
    public function extractMetadataFromFilename(string $filename): array
    {
        $info = [
            'raw_filename' => $filename,
            'client_name' => null,
            'subject_code' => null,
            'exam_date' => null,
            'serial' => null,
        ];

        $basename = pathinfo($filename, PATHINFO_FILENAME);

        // Caso 1: Código de barras Spot Vision estándar: "04490423_IR_ENG7_20260130_112849_0"
        if (preg_match('/^(\d+)_([A-Za-z0-9]+)_([A-Za-z0-9\-]+)_(\d{8})_(\d{6})/', $basename, $m)) {
            $info['serial'] = $m[1];
            $info['initials'] = $m[2];
            $info['subject_code'] = $m[3];
            $dateStr = $m[4]; // 20260130
            $timeStr = $m[5]; // 112849
            try {
                $info['exam_date'] = \Carbon\Carbon::createFromFormat('Ymd His', $dateStr . ' ' . $timeStr);
            } catch (\Exception $e) {}
        }

        // Caso 2: Nombre del paciente como nombre de archivo: "Izamar Rodriguez Cabello.pdf"
        if (empty($info['client_name']) && !preg_match('/^\d{8}_/', $basename)) {
            // Limpiar guiones bajos o caracteres
            $cleanName = trim(str_replace(['_', '-'], ' ', $basename));
            // Si tiene al menos 2 palabras, muy probable nombre de persona
            if (str_word_count($cleanName) >= 2) {
                $info['client_name'] = ucwords(mb_strtolower($cleanName, 'UTF-8'));
            }
        }

        return $info;
    }
}
