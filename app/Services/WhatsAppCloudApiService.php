<?php

namespace App\Services;

use App\Models\Client;
use App\Models\Screening;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class WhatsAppCloudApiService
{
    /**
     * Obtiene el Phone Number ID con respaldo directo de .env si la caché de producción está congelada
     */
    public static function getPhoneId(): ?string
    {
        $id = config('services.whatsapp.phone_id') ?: env('WHATSAPP_PHONE_ID');
        if (!empty($id)) {
            return $id;
        }

        return self::readEnvDirect('WHATSAPP_PHONE_ID');
    }

    /**
     * Obtiene el Access Token de Meta con respaldo directo de .env
     */
    public static function getToken(): ?string
    {
        $token = config('services.whatsapp.token') ?: env('WHATSAPP_ACCESS_TOKEN');
        if (!empty($token)) {
            return $token;
        }

        return self::readEnvDirect('WHATSAPP_ACCESS_TOKEN');
    }

    public static function getApiVersion(): string
    {
        $ver = config('services.whatsapp.api_version') ?: env('WHATSAPP_API_VERSION');
        if (!empty($ver)) {
            return $ver;
        }

        return self::readEnvDirect('WHATSAPP_API_VERSION') ?: 'v21.0';
    }

    public static function isConfigured(): bool
    {
        return !empty(self::getPhoneId()) && !empty(self::getToken());
    }

    /**
     * Prueba la conexión y validez del Access Token y Phone ID con Meta Graph API
     */
    public function testConnection(): array
    {
        if (!self::isConfigured()) {
            return [
                'success' => false,
                'message' => 'Faltan credenciales WHATSAPP_PHONE_ID o WHATSAPP_ACCESS_TOKEN en la configuración.',
            ];
        }

        $phoneId = self::getPhoneId();
        $token = self::getToken();
        $version = self::getApiVersion();

        try {
            $url = "https://graph.facebook.com/{$version}/{$phoneId}";
            $response = Http::withToken($token)
                ->timeout(10)
                ->get($url, [
                    'fields' => 'display_phone_number,verified_name,quality_rating,code_verification_status',
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $displayName = $data['verified_name'] ?? 'Enjoy Vision';
                $phone = $data['display_phone_number'] ?? $phoneId;
                $quality = $data['quality_rating'] ?? 'GREEN';

                return [
                    'success' => true,
                    'message' => "Conexión exitosa con Meta Cloud API. Emisor: {$displayName} ({$phone}) • Calidad: {$quality}",
                    'data' => $data,
                ];
            }

            $error = $response->json()['error'] ?? [];
            $errMsg = $error['message'] ?? $response->body();

            return [
                'success' => false,
                'message' => "Error de Meta API ({$response->status()}): {$errMsg}",
            ];
        } catch (Throwable $e) {
            return [
                'success' => false,
                'message' => 'Fallo al conectar con los servidores de Meta: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Envía el reporte oficial de tamizaje al paciente usando la Cloud API corporativa
     */
    public function sendScreeningReport(Screening $screening): array
    {
        if (!self::isConfigured()) {
            return [
                'success' => false,
                'message' => 'WhatsApp Cloud API no está configurada aún en .env.',
            ];
        }

        $phone = $screening->client?->phone;
        if (empty($phone)) {
            return [
                'success' => false,
                'message' => 'El paciente no tiene un número telefónico registrado.',
            ];
        }

        // Formatear a solo dígitos (E.164 sin signo +)
        $cleanPhone = preg_replace('/[^0-9]/', '', Client::sanitizePhone($phone) ?: $phone);
        if (empty($cleanPhone) || strlen($cleanPhone) < 10) {
            return [
                'success' => false,
                'message' => "El número telefónico '{$phone}' no tiene formato internacional válido.",
            ];
        }

        $phoneId = self::getPhoneId();
        $token = self::getToken();
        $version = self::getApiVersion();
        $messageText = $screening->whatsapp_message_body ?: $screening->generateWhatsAppMessage();

        $url = "https://graph.facebook.com/{$version}/{$phoneId}/messages";

        // Estructura oficial del mensaje de texto
        $payload = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $cleanPhone,
            'type' => 'text',
            'text' => [
                'preview_url' => true,
                'body' => $messageText,
            ],
        ];

        try {
            Log::info("WhatsAppCloudApi: Enviando reporte #{$screening->id} a {$cleanPhone} desde PhoneID {$phoneId}");

            $response = Http::withToken($token)
                ->timeout(15)
                ->post($url, $payload);

            if ($response->successful()) {
                $data = $response->json();
                $wamid = $data['messages'][0]['id'] ?? 'N/A';

                $screening->update([
                    'whatsapp_status' => 'sent',
                    'whatsapp_sent_at' => now(),
                ]);

                Log::info("WhatsAppCloudApi: Reporte #{$screening->id} enviado exitosamente. WAMID: {$wamid}");

                return [
                    'success' => true,
                    'message' => "Reporte enviado exitosamente desde la cuenta oficial Enjoy Vision (ID: {$wamid}).",
                    'wamid' => $wamid,
                ];
            }

            $errorData = $response->json()['error'] ?? [];
            $errorMsg = $errorData['message'] ?? $response->body();
            $errorCode = $errorData['code'] ?? $response->status();

            Log::error("WhatsAppCloudApi error {$errorCode}: {$errorMsg}", [
                'screening_id' => $screening->id,
                'phone' => $cleanPhone,
                'response' => $response->json(),
            ]);

            return [
                'success' => false,
                'message' => "Meta API Error ({$errorCode}): {$errorMsg}",
            ];
        } catch (Throwable $e) {
            Log::error("WhatsAppCloudApi Exception: " . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Error de conexión con WhatsApp Cloud API: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Envía el reporte oficial de Graduación / Receta Óptica desde Enjoy Vision
     */
    public function sendGraduationReport(Screening $screening): array
    {
        if (!self::isConfigured()) {
            return [
                'success' => false,
                'message' => 'WhatsApp Cloud API no está configurada aún en .env.',
            ];
        }

        $phone = $screening->client?->phone;
        if (empty($phone)) {
            return [
                'success' => false,
                'message' => 'El paciente no tiene un número telefónico registrado.',
            ];
        }

        $cleanPhone = preg_replace('/[^0-9]/', '', Client::sanitizePhone($phone) ?: $phone);
        if (empty($cleanPhone) || strlen($cleanPhone) < 10) {
            return [
                'success' => false,
                'message' => "El número telefónico '{$phone}' no tiene formato internacional válido.",
            ];
        }

        $phoneId = self::getPhoneId();
        $token = self::getToken();
        $version = self::getApiVersion();
        $messageText = $screening->generateGraduationWhatsAppMessage();

        $url = "https://graph.facebook.com/{$version}/{$phoneId}/messages";

        $payload = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $cleanPhone,
            'type' => 'text',
            'text' => [
                'preview_url' => true,
                'body' => $messageText,
            ],
        ];

        try {
            Log::info("WhatsAppCloudApi: Enviando graduación #{$screening->id} a {$cleanPhone} desde PhoneID {$phoneId}");

            $response = Http::withToken($token)
                ->timeout(15)
                ->post($url, $payload);

            if ($response->successful()) {
                $data = $response->json();
                $wamid = $data['messages'][0]['id'] ?? 'N/A';

                $screening->update([
                    'graduation_sent_at' => now(),
                ]);

                Log::info("WhatsAppCloudApi: Graduación #{$screening->id} enviada exitosamente. WAMID: {$wamid}");

                return [
                    'success' => true,
                    'message' => "Reporte de graduación enviado exitosamente desde Enjoy Vision (ID: {$wamid}).",
                    'wamid' => $wamid,
                ];
            }

            $errorData = $response->json()['error'] ?? [];
            $errorMsg = $errorData['message'] ?? $response->body();
            $errorCode = $errorData['code'] ?? $response->status();

            Log::error("WhatsAppCloudApi graduación error {$errorCode}: {$errorMsg}", [
                'screening_id' => $screening->id,
                'phone' => $cleanPhone,
                'response' => $response->json(),
            ]);

            return [
                'success' => false,
                'message' => "Meta API Error ({$errorCode}): {$errorMsg}",
            ];
        } catch (Throwable $e) {
            Log::error("WhatsAppCloudApi Exception: " . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Error de conexión con WhatsApp Cloud API: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Respaldo para leer directamente de .env cuando la caché de configuración en producción está congelada
     */
    protected static function readEnvDirect(string $key): ?string
    {
        $envPath = base_path('.env');
        if (file_exists($envPath)) {
            $lines = @file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            if ($lines) {
                foreach ($lines as $line) {
                    $line = trim($line);
                    if (str_starts_with($line, '#')) continue;
                    if (str_starts_with($line, "{$key}=")) {
                        $val = trim(substr($line, strlen("{$key}=")));
                        $clean = trim($val, "\"'");
                        if (!empty($clean)) {
                            return $clean;
                        }
                    }
                }
            }
        }

        return null;
    }
}
