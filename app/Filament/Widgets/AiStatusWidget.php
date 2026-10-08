<?php

namespace App\Filament\Widgets;

use App\Models\Screening;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Http;
use Throwable;

class AiStatusWidget extends Widget
{
    protected static string $view = 'filament.widgets.ai-status-widget';

    protected static ?int $sort = -5;

    protected int | string | array $columnSpan = 'full';

    public ?string $testStatus = null;
    public ?string $testMessage = null;
    public bool $isTesting = false;

    public function testConnection(): void
    {
        $this->isTesting = true;
        $key = config('services.gemini.api_key', env('GEMINI_API_KEY'));
        $model = config('services.gemini.model', env('GEMINI_MODEL', 'gemini-1.5-flash'));

        if (empty($key)) {
            $this->testStatus = 'danger';
            $this->testMessage = 'Clave GEMINI_API_KEY no detectada en la configuración.';
            $this->isTesting = false;
            return;
        }

        try {
            $startTime = microtime(true);
            $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$key}";

            $response = Http::timeout(8)->post($url, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => 'ping']
                        ]
                    ]
                ]
            ]);

            $elapsedMs = round((microtime(true) - $startTime) * 1000);

            if ($response->successful()) {
                $this->testStatus = 'success';
                $this->testMessage = "Conexión exitosa con Google Gemini ({$model}) • Latencia: {$elapsedMs} ms";
            } else {
                $this->testStatus = 'danger';
                $body = $response->json();
                $err = $body['error']['message'] ?? $response->body();
                $this->testMessage = "Error {$response->status()}: " . substr($err, 0, 150);
            }
        } catch (Throwable $e) {
            $this->testStatus = 'danger';
            $this->testMessage = 'Fallo de conexión: ' . substr($e->getMessage(), 0, 150);
        } finally {
            $this->isTesting = false;
        }
    }

    public function getViewData(): array
    {
        $key = config('services.gemini.api_key', env('GEMINI_API_KEY'));
        $model = config('services.gemini.model', env('GEMINI_MODEL', 'gemini-1.5-flash'));
        $isConfigured = !empty($key);

        $totalScreenings = Screening::count();
        $aiCount = Screening::where('extraction_method', 'ai_vision')->count();
        $programmaticCount = Screening::where('extraction_method', 'programmatic')->count();

        $aiPercentage = $totalScreenings > 0
            ? round(($aiCount / $totalScreenings) * 100, 1)
            : 0;

        $lastAiScreening = Screening::where('extraction_method', 'ai_vision')
            ->latest()
            ->with('client')
            ->first();

        // Ocultar la clave para seguridad (mostrar primeros 6 y últimos 4)
        $maskedKey = $isConfigured
            ? substr($key, 0, 6) . '...' . substr($key, -4)
            : null;

        return [
            'isConfigured' => $isConfigured,
            'model' => $model,
            'maskedKey' => $maskedKey,
            'totalScreenings' => $totalScreenings,
            'aiCount' => $aiCount,
            'programmaticCount' => $programmaticCount,
            'aiPercentage' => $aiPercentage,
            'lastAiScreening' => $lastAiScreening,
        ];
    }
}
