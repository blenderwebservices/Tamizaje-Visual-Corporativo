<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SpotVisionAiExtractor
{
    /**
     * Intenta extraer los datos del reporte usando Gemini o OpenAI Multimodal Vision
     */
    public function extractFromImage(string $imageFullPath): ?array
    {
        if (!file_exists($imageFullPath)) {
            return null;
        }

        // 1. Probar Gemini API si está configurada
        $geminiKey = config('services.gemini.api_key', env('GEMINI_API_KEY'));
        if (!empty($geminiKey)) {
            try {
                $result = $this->callGeminiVision($imageFullPath, $geminiKey);
                if ($result) {
                    return $result;
                }
            } catch (Exception $e) {
                Log::warning('Gemini Vision extraction failed: ' . $e->getMessage());
            }
        }

        // 2. Probar OpenAI API si está configurada
        $openAiKey = config('services.openai.api_key', env('OPENAI_API_KEY'));
        if (!empty($openAiKey)) {
            try {
                $result = $this->callOpenAiVision($imageFullPath, $openAiKey);
                if ($result) {
                    return $result;
                }
            } catch (Exception $e) {
                Log::warning('OpenAI Vision extraction failed: ' . $e->getMessage());
            }
        }

        return null;
    }

    protected function getPrompt(): string
    {
        return <<<PROMPT
Eres un experto en lectura clínica de reportes de autorrefractometría computarizada del equipo Welch Allyn Spot Vision Screener.
Analiza la imagen adjunta y extrae de forma precisa todos los campos. Responde ÚNICAMENTE con un objeto JSON sin formato markdown ni explicaciones adicionales, con esta estructura exacta:

{
  "subject_code": "ENG7",
  "first_name": "Izamar",
  "last_name": "Rodriguez Cabello",
  "full_name": "Izamar Rodriguez Cabello",
  "gender": "H",
  "birth_date": "1992-05-05",
  "age": null,
  "exam_date": "2026-01-30 11:28:00",
  "wears_glasses": true,
  "status_label": "Selección finalizada",
  "overall_status": "PASA",
  "interpupillary_distance_mm": 70.0,
  "cylinder_mode": "-CIL",
  "od": {
    "sphere_se": -0.50,
    "sphere_ds": 0.00,
    "cylinder_dc": -0.75,
    "axis": 163,
    "pupil_size_mm": 3.8,
    "gaze_v": "↓ 1°",
    "gaze_h": "0°"
  },
  "os": {
    "sphere_se": -0.50,
    "sphere_ds": -0.50,
    "cylinder_dc": -0.25,
    "axis": 30,
    "pupil_size_mm": 3.9,
    "gaze_v": "↓ 1°",
    "gaze_h": "← 1°"
  },
  "barcode_code": "04490423_IR_ENG7_20260130_112849_0",
  "device_serial": "04490423",
  "possible_conditions": ["Astigmatismo leve"]
}

Reglas:
- Si overall_status dice "Selección finalizada" o "Todas las mediciones en rangos normales" y no hay afección grave, clasifica como "PASA" o "REMITIR" según los valores fuera de rango.
- Los valores numéricos deben ser números flotantes con signo, no cadenas con comillas.
PROMPT;
    }

    protected function callGeminiVision(string $imagePath, string $apiKey): ?array
    {
        $imageData = base64_encode(file_get_contents($imagePath));
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$apiKey}";

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->timeout(30)->post($url, [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $this->getPrompt()],
                        [
                            'inlineData' => [
                                'mimeType' => 'image/png',
                                'data' => $imageData,
                            ]
                        ]
                    ]
                ]
            ],
            'generationConfig' => [
                'temperature' => 0.1,
                'responseMimeType' => 'application/json',
            ]
        ]);

        if ($response->successful()) {
            $json = $response->json();
            $rawText = $json['candidates'][0]['content']['parts'][0]['text'] ?? null;
            if ($rawText) {
                return json_decode($rawText, true);
            }
        }

        return null;
    }

    protected function callOpenAiVision(string $imagePath, string $apiKey): ?array
    {
        $imageData = base64_encode(file_get_contents($imagePath));
        $response = Http::withToken($apiKey)->timeout(30)->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-4o-mini',
            'messages' => [
                [
                    'role' => 'user',
                    'content' => [
                        ['type' => 'text', 'text' => $this->getPrompt()],
                        [
                            'type' => 'image_url',
                            'image_url' => [
                                'url' => 'data:image/png;base64,' . $imageData,
                            ]
                        ]
                    ]
                ]
            ],
            'response_format' => ['type' => 'json_object'],
            'temperature' => 0.1,
        ]);

        if ($response->successful()) {
            $json = $response->json();
            $content = $json['choices'][0]['message']['content'] ?? null;
            if ($content) {
                return json_decode($content, true);
            }
        }

        return null;
    }
}
