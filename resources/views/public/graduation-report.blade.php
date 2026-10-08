<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Graduación Visual • Enjoy Vision | {{ $client->full_name }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: #ffffff !important; color: #000000 !important; }
            .print-card { border: 1px solid #cbd5e1 !important; box-shadow: none !important; background: #ffffff !important; color: #000000 !important; }
            .print-text-dark { color: #0f172a !important; }
            .print-bg-light { background: #f8fafc !important; }
            .print-border { border-color: #cbd5e1 !important; }
        }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex flex-col justify-between antialiased selection:bg-emerald-500 selection:text-white">

    <!-- Encabezado Clínico de Pantalla (no-print) -->
    <header class="no-print bg-slate-900 border-b border-slate-800 py-3.5 px-6 sticky top-0 z-40 backdrop-blur bg-slate-900/95">
        <div class="max-w-3xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-indigo-500/20 border border-indigo-500/30 flex items-center justify-center text-xl shadow-inner">
                    👓
                </div>
                <div>
                    <h1 class="text-sm font-bold text-white leading-tight">Enjoy Vision</h1>
                    <p class="text-[11px] text-indigo-400 font-semibold tracking-wide">Reporte Oficial de Graduación Visual</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button
                    onclick="window.print()"
                    class="px-3.5 py-1.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-xs font-semibold text-white shadow transition flex items-center gap-1.5"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    <span>Imprimir / PDF</span>
                </button>
            </div>
        </div>
    </header>

    <!-- Contenido Principal: Hoja de Receta Óptica -->
    <main class="flex-1 py-6 px-4 flex justify-center">
        <div class="w-full max-w-3xl space-y-6">

            <!-- Documento de Prescripción (Aesthetic Clean Card) -->
            <div class="print-card bg-slate-900 border border-slate-800 rounded-3xl p-6 sm:p-9 shadow-2xl relative overflow-hidden">

                <!-- Header de la Receta -->
                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 border-b border-slate-800 print-border pb-6">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold tracking-wide bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 print-text-dark">
                                RECETA & PRESCRIPCIÓN ÓPTICA
                            </span>
                            <span class="text-xs text-slate-400 font-mono">
                                #{{ $screening->client?->client_code ?? $screening->subject_code }}
                            </span>
                        </div>
                        <h2 class="text-2xl sm:text-3xl font-black text-white print-text-dark">
                            {{ $client->full_name }}
                        </h2>
                        <div class="text-xs text-slate-400 print-text-dark mt-2 flex flex-wrap gap-x-4 gap-y-1">
                            <span><strong>Edad:</strong> {{ $client->age ? $client->age . ' años' : ($client->birth_date ? \Carbon\Carbon::parse($client->birth_date)->age . ' años' : 'No especificada') }}</span>
                            @if($client->phone)
                                <span><strong>Teléfono:</strong> {{ $client->phone }}</span>
                            @endif
                            <span><strong>Fecha:</strong> {{ $screening->exam_date ? $screening->exam_date->format('d/m/Y') : now()->format('d/m/Y') }}</span>
                            @if($company)
                                <span><strong>Empresa:</strong> {{ $company->name }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Logotipo / Insignia Enjoy Vision -->
                    <div class="text-right sm:text-right flex sm:flex-col items-center sm:items-end justify-between">
                        <div class="text-lg font-black tracking-wider text-white print-text-dark">
                            ENJOY<span class="text-indigo-400">VISION</span>
                        </div>
                        <div class="text-[11px] text-slate-400 print-text-dark">
                            Salud Visual Corporativa
                        </div>
                    </div>
                </div>

                <!-- Tabla Principal de Graduación Optométrica -->
                <div class="mt-6">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-bold uppercase tracking-wider text-indigo-400 print-text-dark flex items-center gap-2">
                            <span>🔍</span> Fórmula Optométrica Confirmada
                        </h3>
                        <span class="text-xs font-medium text-slate-400 print-text-dark">
                            Modo: {{ $screening->cylinder_mode ?? '-CIL' }}
                        </span>
                    </div>

                    <div class="overflow-x-auto rounded-2xl border border-slate-800 print-border">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-slate-800/80 print-bg-light text-slate-300 print-text-dark text-xs uppercase tracking-wider font-semibold border-b border-slate-700 print-border">
                                <tr>
                                    <th class="py-3 px-4">Ojo</th>
                                    <th class="py-3 px-4 text-center">Esfera (SPH)</th>
                                    <th class="py-3 px-4 text-center">Cilindro (CYL)</th>
                                    <th class="py-3 px-4 text-center">Eje (AXIS)</th>
                                    <th class="py-3 px-4 text-center bg-indigo-950/40 print-bg-light text-indigo-300 print-text-dark font-bold">DNP (mm)</th>
                                    <th class="py-3 px-4 text-center bg-indigo-950/40 print-bg-light text-indigo-300 print-text-dark font-bold">ADD (Cerca)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800 print-border text-slate-200 print-text-dark font-mono">
                                <!-- Ojo Derecho (OD) -->
                                <tr class="hover:bg-slate-800/30 transition">
                                    <td class="py-3.5 px-4 font-bold font-sans flex items-center gap-2">
                                        <span class="w-2.5 h-2.5 rounded-full bg-blue-400"></span>
                                        <span>OD (Derecho)</span>
                                    </td>
                                    <td class="py-3.5 px-4 text-center text-base font-bold">
                                        {{ $screening->od_sphere_ds !== null ? sprintf('%+.2f', $screening->od_sphere_ds) : ($screening->od_sphere_se !== null ? sprintf('%+.2f', $screening->od_sphere_se) : '0.00') }}
                                    </td>
                                    <td class="py-3.5 px-4 text-center text-base font-bold">
                                        {{ $screening->od_cylinder_dc !== null ? sprintf('%.2f', $screening->od_cylinder_dc) : '0.00' }}
                                    </td>
                                    <td class="py-3.5 px-4 text-center text-base">
                                        {{ $screening->od_axis !== null ? $screening->od_axis . '°' : '—' }}
                                    </td>
                                    <td class="py-3.5 px-4 text-center text-base font-bold bg-indigo-950/20 print-bg-light text-indigo-200 print-text-dark">
                                        {{ $screening->od_dnp_mm !== null ? $screening->od_dnp_mm . ' mm' : '—' }}
                                    </td>
                                    <td class="py-3.5 px-4 text-center text-base font-bold bg-indigo-950/20 print-bg-light text-indigo-200 print-text-dark">
                                        {{ $screening->od_add !== null ? sprintf('+%.2f', $screening->od_add) : '—' }}
                                    </td>
                                </tr>

                                <!-- Ojo Izquierdo (OS) -->
                                <tr class="hover:bg-slate-800/30 transition">
                                    <td class="py-3.5 px-4 font-bold font-sans flex items-center gap-2">
                                        <span class="w-2.5 h-2.5 rounded-full bg-indigo-400"></span>
                                        <span>OS (Izquierdo)</span>
                                    </td>
                                    <td class="py-3.5 px-4 text-center text-base font-bold">
                                        {{ $screening->os_sphere_ds !== null ? sprintf('%+.2f', $screening->os_sphere_ds) : ($screening->os_sphere_se !== null ? sprintf('%+.2f', $screening->os_sphere_se) : '0.00') }}
                                    </td>
                                    <td class="py-3.5 px-4 text-center text-base font-bold">
                                        {{ $screening->os_cylinder_dc !== null ? sprintf('%.2f', $screening->os_cylinder_dc) : '0.00' }}
                                    </td>
                                    <td class="py-3.5 px-4 text-center text-base">
                                        {{ $screening->os_axis !== null ? $screening->os_axis . '°' : '—' }}
                                    </td>
                                    <td class="py-3.5 px-4 text-center text-base font-bold bg-indigo-950/20 print-bg-light text-indigo-200 print-text-dark">
                                        {{ $screening->os_dnp_mm !== null ? $screening->os_dnp_mm . ' mm' : '—' }}
                                    </td>
                                    <td class="py-3.5 px-4 text-center text-base font-bold bg-indigo-950/20 print-bg-light text-indigo-200 print-text-dark">
                                        {{ $screening->os_add !== null ? sprintf('+%.2f', $screening->os_add) : '—' }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Parámetros Anatómicos y Pupilares -->
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mt-4">
                    <div class="p-3.5 rounded-2xl bg-slate-800/50 print-bg-light border border-slate-800 print-border">
                        <span class="text-[11px] font-semibold text-slate-400 print-text-dark block uppercase tracking-wider">
                            Distancia Pupilar (DIP)
                        </span>
                        <span class="text-lg font-bold text-white print-text-dark mt-0.5 block">
                            {{ $screening->interpupillary_distance_mm ? $screening->interpupillary_distance_mm . ' mm' : 'Calculada por DNP' }}
                        </span>
                    </div>

                    <div class="p-3.5 rounded-2xl bg-slate-800/50 print-bg-light border border-slate-800 print-border">
                        <span class="text-[11px] font-semibold text-slate-400 print-text-dark block uppercase tracking-wider">
                            Diámetro Pupilar (OD / OS)
                        </span>
                        <span class="text-sm font-bold text-white print-text-dark mt-1 block font-mono">
                            {{ $screening->od_pupil_size_mm ? $screening->od_pupil_size_mm . ' mm' : '—' }} /
                            {{ $screening->os_pupil_size_mm ? $screening->os_pupil_size_mm . ' mm' : '—' }}
                        </span>
                    </div>

                    <div class="p-3.5 rounded-2xl bg-slate-800/50 print-bg-light border border-slate-800 print-border col-span-2 sm:col-span-1">
                        <span class="text-[11px] font-semibold text-slate-400 print-text-dark block uppercase tracking-wider">
                            Uso Previo de Lentes
                        </span>
                        <span class="text-sm font-bold {{ $screening->wears_glasses ? 'text-emerald-400' : 'text-slate-300' }} print-text-dark mt-1 block">
                            {{ $screening->wears_glasses ? 'Sí, evaluado con lentes' : 'Sin corrección previa' }}
                        </span>
                    </div>
                </div>

                <!-- Sección: Validación y Notas del Optometrista -->
                <div class="mt-6 space-y-4">
                    <!-- Notas del Optometrista -->
                    @if(!empty($screening->optometrist_notes))
                        <div class="p-4 rounded-2xl bg-indigo-950/30 print-bg-light border border-indigo-900/60 print-border">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-indigo-300 print-text-dark flex items-center gap-1.5 mb-1.5">
                                <span>📋</span> Observaciones Clínicas y Notas de Graduación:
                            </h4>
                            <p class="text-sm text-slate-200 print-text-dark leading-relaxed whitespace-pre-line">
                                {{ $screening->optometrist_notes }}
                            </p>
                        </div>
                    @endif

                    <!-- Recomendaciones y Síntesis -->
                    <div class="p-4 rounded-2xl bg-slate-800/40 print-bg-light border border-slate-800 print-border">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-300 print-text-dark flex items-center gap-1.5 mb-1.5">
                            <span>💡</span> Recomendación de Micas y Tratamientos:
                        </h4>
                        <p class="text-sm text-slate-300 print-text-dark leading-relaxed">
                            {{ $screening->recommendations ?: 'Se recomienda protección antirreflejante de alta definición con filtro para luz azul (pantallas) y revisión preventiva anual.' }}
                        </p>
                    </div>
                </div>

                <!-- Pie de Firma y Validación Profesional -->
                <div class="mt-8 pt-6 border-t border-slate-800 print-border flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div>
                        <p class="text-xs text-slate-400 print-text-dark">Evaluado y validado por:</p>
                        <p class="text-sm font-bold text-white print-text-dark">
                            {{ $screening->optometrist_name ?: 'Especialista en Optometría Clínica' }}
                        </p>
                        <p class="text-[11px] text-indigo-400 font-medium">Enjoy Vision • Cédula & Acreditación de Salud Visual</p>
                    </div>

                    <div class="text-center sm:text-right">
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 print-text-dark">
                            ✓ Graduación Verificada y Lista para Fabricación
                        </span>
                    </div>
                </div>
            </div>

            <!-- Botones de Acción (no-print) -->
            <div class="no-print flex flex-col sm:flex-row gap-3">
                <a
                    href="{{ $screening->graduation_whatsapp_url }}"
                    target="_blank"
                    class="flex-1 py-3 px-4 rounded-2xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-sm shadow-lg transition flex items-center justify-center gap-2"
                >
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                    <span>Compartir Receta por WhatsApp</span>
                </a>

                <a
                    href="{{ route('report.show', ['uuid' => $screening->uuid]) }}"
                    class="py-3 px-4 rounded-2xl bg-slate-800 hover:bg-slate-700 text-slate-200 font-semibold text-sm border border-slate-700 transition flex items-center justify-center gap-2"
                >
                    <span>Ver Reporte Preventivo SpotVision</span>
                    <span>→</span>
                </a>
            </div>

        </div>
    </main>

    <!-- Footer Simple -->
    <footer class="no-print py-6 text-center text-xs text-slate-500 border-t border-slate-900 mt-6">
        <p>© {{ date('Y') }} Enjoy Vision • Prescripción Optométrica Digital y Salud Visual Corporativa.</p>
    </footer>

</body>
</html>
