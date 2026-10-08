<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeadersMiddleware
{
    /**
     * Handle an incoming request.
     * Aplica las directivas del documento docs/AuditoriaDeSeguridad.md
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Limpieza de Prototype Pollution en entradas JSON o arreglos
        if ($request->isJson() || $request->has('__proto__') || $request->has('constructor') || $request->has('prototype')) {
            $cleaned = $this->stripPollution($request->all());
            $request->replace($cleaned);
        }

        $response = $next($request);

        // Cabeceras de seguridad requeridas por Pilar 2 y Pilar 8
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'geolocation=(), camera=(), microphone=(), payment=()');

        // Content Security Policy adaptada para Filament y vistas públicas
        $csp = "default-src 'self'; " .
               "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.tailwindcss.com; " .
               "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; " .
               "font-src 'self' https://fonts.gstatic.com data:; " .
               "img-src 'self' data: blob:; " .
               "connect-src 'self' blob: data:; " .
               "object-src 'none'; " .
               "base-uri 'self';";

        $response->headers->set('Content-Security-Policy', $csp);

        return $response;
    }

    /**
     * Pilar 5: Mitigación de Prototype Pollution y Deserialización
     */
    protected function stripPollution(mixed $data): mixed
    {
        if (!is_array($data)) {
            return $data;
        }

        $clean = [];
        foreach ($data as $key => $value) {
            if ($key === '__proto__' || $key === 'constructor' || $key === 'prototype') {
                continue;
            }
            $clean[$key] = is_array($value) ? $this->stripPollution($value) : $value;
        }

        return $clean;
    }
}
