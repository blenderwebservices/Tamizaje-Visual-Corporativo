<?php

namespace App\Services;

use Carbon\Carbon;
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
            'barcode_code' => null,
        ];

        $basename = pathinfo($filename, PATHINFO_FILENAME);

        // Caso 1: Código de barras Spot Vision estándar: "04490423_IR_ENGM01_20260129_133246_0"
        if (preg_match('/^(\d+)_([A-Za-z0-9]+)_([A-Za-z0-9\-]+)_(\d{8})_(\d{6})/', $basename, $m)) {
            $info['serial'] = $m[1];
            $info['initials'] = $m[2];
            $info['subject_code'] = $m[3];
            $info['barcode_code'] = $basename;
            $dateStr = $m[4]; // 20260129
            $timeStr = $m[5]; // 133246
            try {
                $info['exam_date'] = Carbon::createFromFormat('Ymd His', $dateStr . ' ' . $timeStr);
            } catch (\Exception $e) {}
        }

        // Caso 2: Nombre del paciente como nombre de archivo: "Izamar Rodriguez Cabello.pdf"
        if (empty($info['client_name']) && !preg_match('/^\d{8}_/', $basename)) {
            $cleanName = trim(str_replace(['_', '-'], ' ', $basename));
            if (str_word_count($cleanName) >= 2) {
                $info['client_name'] = ucwords(mb_strtolower($cleanName, 'UTF-8'));
            }
        }

        return $info;
    }

    /**
     * Extrae texto mediante OCR nativo de macOS (Apple Vision Framework)
     */
    public function extractOcrData(?string $previewFullPath): array
    {
        if (!$previewFullPath || !file_exists($previewFullPath)) {
            return [];
        }

        $ocrBinary = base_path('bin/spotvision_ocr');
        if (!file_exists($ocrBinary) || !is_executable($ocrBinary)) {
            return [];
        }

        $output = @shell_exec(escapeshellcmd($ocrBinary) . ' ' . escapeshellarg($previewFullPath));
        if (empty($output)) {
            return [];
        }

        $lines = array_values(array_filter(array_map('trim', explode(PHP_EOL, $output))));
        $data = [
            'subject_code' => null,
            'first_name' => null,
            'last_name' => null,
            'full_name' => null,
            'birth_date' => null,
            'exam_date' => null,
            'barcode_code' => null,
            'wears_glasses' => false,
            'interpupillary_distance_mm' => null,
            'od' => [],
            'os' => [],
        ];

        // Buscar fecha de nacimiento, código de barras y datos demográficos
        foreach ($lines as $i => $line) {
            // Código de barras en el footer (ej. 04490423_IR_ENGM01_20260129_133246_0)
            if (preg_match('/(\d{8}_[A-Za-z0-9]+_([A-Za-z0-9\-]+)_(\d{8})_(\d{6})_\d)/', $line, $m)) {
                $data['barcode_code'] = $m[1];
                if (empty($data['subject_code'])) {
                    $data['subject_code'] = $m[2];
                }
            }

            // Fecha de nacimiento en formato dd/mm/yyyy
            if (preg_match('/^(\d{2})\/(\d{2})\/(\d{4})$/', $line, $m)) {
                $data['birth_date'] = "{$m[3]}-{$m[2]}-{$m[1]}";
            }

            // Fecha y hora del examen (ej. 29/01/2026 1:32 pm)
            if (preg_match('/(\d{2}\/\d{2}\/\d{4}\s+\d{1,2}:\d{2}\s+(?:am|pm))/i', $line, $m)) {
                try {
                    $data['exam_date'] = Carbon::createFromFormat('d/m/Y g:i a', $m[1])->toDateTimeString();
                } catch (\Exception $e) {}
            }

            // ¿Usa gafas?
            if (str_contains(mb_strtolower($line), 'usa gafas')) {
                $data['wears_glasses'] = true;
            }

            // Distancia Interpupilar (ej. "70 mm" o "66 mm")
            if (preg_match('/^(\d{2})\s*mm$/i', $line, $m) || preg_match('/^(\d{2})$/', $line, $m)) {
                $val = (float)$m[1];
                if ($val >= 50 && $val <= 80 && empty($data['interpupillary_distance_mm'])) {
                    $data['interpupillary_distance_mm'] = $val;
                }
            }
        }

        // Buscar Nombres y Apellidos en la cuadrícula de datos demográficos
        for ($i = 0; $i < count($lines); $i++) {
            if ($lines[$i] === 'APELLIDO') {
                // Las siguientes líneas contienen: ID Sujeto, Fecha, Fecha Nacimiento, Nombre, Apellido
                // Buscar hacia abajo los primeros textos alfabéticos
                $nameCandidates = [];
                for ($j = $i + 1; $j < min(count($lines), $i + 10); $j++) {
                    $candidate = $lines[$j];
                    if (str_contains($candidate, 'gafas') || str_contains($candidate, 'POSIBLE') || preg_match('/\d/', $candidate)) {
                        continue;
                    }
                    if (preg_match('/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/u', $candidate) && strlen($candidate) >= 3) {
                        $nameCandidates[] = $candidate;
                    }
                }

                if (count($nameCandidates) >= 2) {
                    $data['first_name'] = $nameCandidates[0];
                    $data['last_name'] = $nameCandidates[1];
                    $data['full_name'] = $nameCandidates[0] . ' ' . $nameCandidates[1];
                } elseif (count($nameCandidates) === 1) {
                    $data['full_name'] = $nameCandidates[0];
                }
                break;
            }
        }

        // Parsear refracciones OD y OS
        $refractionMatches = [];
        foreach ($lines as $line) {
            // Patrón: "+0.25 -1.25 @172°" o "-0.50 -0.25 @30°" o "-1.25@172°"
            if (preg_match('/([+-]?\d+\.\d{2})[^\d+-]*([+-]?\d+\.\d{2})[^\d]*@(\d+)°?/i', $line, $m)) {
                $refractionMatches[] = [
                    'ds' => (float)$m[1],
                    'dc' => (float)$m[2],
                    'axis' => (int)$m[3],
                ];
            } elseif (preg_match('/([+-]?\d+\.\d{2})@(\d+)°?/i', $line, $m)) {
                $refractionMatches[] = [
                    'dc' => (float)$m[1],
                    'axis' => (int)$m[2],
                ];
            }
        }

        if (count($refractionMatches) >= 2) {
            $data['od']['sphere_ds'] = $refractionMatches[0]['ds'] ?? 0.0;
            $data['od']['cylinder_dc'] = $refractionMatches[0]['dc'] ?? null;
            $data['od']['axis'] = $refractionMatches[0]['axis'] ?? null;
            $data['od']['sphere_se'] = round(($data['od']['sphere_ds'] ?? 0) + (($data['od']['cylinder_dc'] ?? 0) / 2), 2);

            $data['os']['sphere_ds'] = $refractionMatches[1]['ds'] ?? 0.0;
            $data['os']['cylinder_dc'] = $refractionMatches[1]['dc'] ?? null;
            $data['os']['axis'] = $refractionMatches[1]['axis'] ?? null;
            $data['os']['sphere_se'] = round(($data['os']['sphere_ds'] ?? 0) + (($data['os']['cylinder_dc'] ?? 0) / 2), 2);
        }

        return array_filter($data, fn ($v) => $v !== null && $v !== []);
    }
}
