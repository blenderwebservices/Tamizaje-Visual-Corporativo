<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Tamizaje Visual | {{ $client->full_name }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
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
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex flex-col justify-between antialiased selection:bg-emerald-500 selection:text-white">
    <!-- Encabezado Clínico -->
    <header class="bg-slate-900 border-b border-slate-800 py-4 px-6 sticky top-0 z-40 backdrop-blur bg-slate-900/90">
        <div class="max-w-2xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-500/20 border border-emerald-500/30 flex items-center justify-center text-xl">
                    👁️
                </div>
                <div>
                    <h1 class="text-sm font-bold text-white leading-tight">Visual Corporativo</h1>
                    <p class="text-[11px] text-emerald-400 font-medium">Reporte Oficial Spot™ Vision</p>
                </div>
            </div>
            <a
                href="{{ route('report.download', ['uuid' => $screening->uuid]) }}"
                class="px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-xs font-semibold text-slate-200 border border-slate-700 transition flex items-center gap-1.5"
            >
                <span>Descargar PDF</span>
                <span>↓</span>
            </a>
        </div>
    </header>

    <!-- Cuerpo del Reporte -->
    <main class="flex-1 py-8 px-4 flex justify-center">
        <div class="w-full max-w-2xl space-y-6">

            <!-- Tarjeta de Paciente y Resultado General (Fase 2 y 3) -->
            <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 sm:p-8 shadow-xl relative overflow-hidden">
                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 border-b border-slate-800 pb-6">
                    <div>
                        <span class="text-xs font-bold text-emerald-400 uppercase tracking-widest block mb-1">
                            Resultados del Examen
                        </span>
                        <h2 class="text-2xl sm:text-3xl font-black text-white">
                            {{ $client->full_name }}
                        </h2>
                        <div class="text-xs text-slate-400 mt-2 flex flex-wrap gap-x-4 gap-y-1">
                            <span>🏢 <strong>Empresa:</strong> {{ $company?->name ?: 'Jornada Corporativa' }}</span>
                            <span>📅 <strong>Fecha:</strong> {{ $screening->exam_date?->format('d/m/Y h:i a') }}</span>
                            @if($screening->subject_code)
                                <span>🏷️ <strong>ID Sujeto:</strong> {{ $screening->subject_code }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Insignia de Resultado (PASA / REMITIR) -->
                    <div class="self-start">
                        @if($screening->screening_status === 'pass')
                            <div class="px-4 py-2 rounded-2xl bg-emerald-500/20 border border-emerald-500/40 text-emerald-300 text-center">
                                <span class="text-xs font-bold uppercase tracking-wider block">Resultado General</span>
                                <span class="text-xl font-extrabold flex items-center justify-center gap-1">
                                    ✓ PASA
                                </span>
                            </div>
                        @else
                            <div class="px-4 py-2 rounded-2xl bg-amber-500/20 border border-amber-500/40 text-amber-300 text-center">
                                <span class="text-xs font-bold uppercase tracking-wider block">Resultado General</span>
                                <span class="text-xl font-extrabold flex items-center justify-center gap-1">
                                    ⚠️ REMITIR
                                </span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Análisis Rápido en Lenguaje Humano -->
                <div class="mt-6 p-4 rounded-2xl bg-slate-800/80 border border-slate-700">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1 flex items-center gap-1.5">
                        <span>📋</span> Análisis Rápido del Optometrista
                    </h3>
                    <p class="text-sm text-slate-200 leading-relaxed">
                        {{ $screening->quick_analysis_summary }}
                    </p>

                    @if(!empty($screening->findings) && is_array($screening->findings))
                        <div class="mt-3 flex flex-wrap items-center gap-2 pt-2 border-t border-slate-700/60">
                            <span class="text-xs text-slate-400 font-medium">Condición Detectada:</span>
                            @foreach($screening->findings as $finding)
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-500/10 border border-emerald-500/30 text-emerald-300">
                                    {{ $finding }}
                                </span>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Mediciones Ópticas (OD vs OS) -->
                <div class="mt-6">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">
                        Mediciones Computarizadas de Autorrefracción
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Ojo Derecho (OD) -->
                        <div class="p-5 rounded-2xl bg-slate-800/50 border border-slate-700/80">
                            <div class="flex items-center justify-between mb-3 border-b border-slate-700 pb-2">
                                <span class="font-bold text-emerald-400 text-sm">Ojo Derecho (OD)</span>
                                <span class="text-xs text-slate-400">Pupila: {{ $screening->od_pupil_size_mm ? $screening->od_pupil_size_mm . ' mm' : '-' }}</span>
                            </div>

                            <div class="grid grid-cols-3 gap-2 text-center">
                                <div class="p-2 rounded-xl bg-slate-900/60">
                                    <span class="text-[10px] text-slate-400 block">Esfera (DS)</span>
                                    <span class="text-sm font-bold text-white font-mono">
                                        {{ $screening->od_sphere_ds !== null ? number_format($screening->od_sphere_ds, 2) : '-' }}
                                    </span>
                                </div>
                                <div class="p-2 rounded-xl bg-slate-900/60">
                                    <span class="text-[10px] text-slate-400 block">Cilindro (DC)</span>
                                    <span class="text-sm font-bold text-white font-mono">
                                        {{ $screening->od_cylinder_dc !== null ? number_format($screening->od_cylinder_dc, 2) : '-' }}
                                    </span>
                                </div>
                                <div class="p-2 rounded-xl bg-slate-900/60">
                                    <span class="text-[10px] text-slate-400 block">Eje</span>
                                    <span class="text-sm font-bold text-white font-mono">
                                        {{ $screening->od_axis !== null ? $screening->od_axis . '°' : '-' }}
                                    </span>
                                </div>
                            </div>

                            <div class="mt-3 text-[11px] text-slate-400 flex justify-between">
                                <span>Eq. Esférico (SE): <strong>{{ $screening->od_sphere_se !== null ? number_format($screening->od_sphere_se, 2) : '-' }}</strong></span>
                                <span>Alineación: <strong>{{ $screening->od_gaze_v ?: '0°' }}</strong></span>
                            </div>
                        </div>

                        <!-- Ojo Izquierdo (OS) -->
                        <div class="p-5 rounded-2xl bg-slate-800/50 border border-slate-700/80">
                            <div class="flex items-center justify-between mb-3 border-b border-slate-700 pb-2">
                                <span class="font-bold text-indigo-400 text-sm">Ojo Izquierdo (OS)</span>
                                <span class="text-xs text-slate-400">Pupila: {{ $screening->os_pupil_size_mm ? $screening->os_pupil_size_mm . ' mm' : '-' }}</span>
                            </div>

                            <div class="grid grid-cols-3 gap-2 text-center">
                                <div class="p-2 rounded-xl bg-slate-900/60">
                                    <span class="text-[10px] text-slate-400 block">Esfera (DS)</span>
                                    <span class="text-sm font-bold text-white font-mono">
                                        {{ $screening->os_sphere_ds !== null ? number_format($screening->os_sphere_ds, 2) : '-' }}
                                    </span>
                                </div>
                                <div class="p-2 rounded-xl bg-slate-900/60">
                                    <span class="text-[10px] text-slate-400 block">Cilindro (DC)</span>
                                    <span class="text-sm font-bold text-white font-mono">
                                        {{ $screening->os_cylinder_dc !== null ? number_format($screening->os_cylinder_dc, 2) : '-' }}
                                    </span>
                                </div>
                                <div class="p-2 rounded-xl bg-slate-900/60">
                                    <span class="text-[10px] text-slate-400 block">Eje</span>
                                    <span class="text-sm font-bold text-white font-mono">
                                        {{ $screening->os_axis !== null ? $screening->os_axis . '°' : '-' }}
                                    </span>
                                </div>
                            </div>

                            <div class="mt-3 text-[11px] text-slate-400 flex justify-between">
                                <span>Eq. Esférico (SE): <strong>{{ $screening->os_sphere_se !== null ? number_format($screening->os_sphere_se, 2) : '-' }}</strong></span>
                                <span>Alineación: <strong>{{ $screening->os_gaze_v ?: '0°' }}</strong></span>
                            </div>
                        </div>
                    </div>

                    @if($screening->interpupillary_distance_mm)
                        <div class="mt-3 text-center text-xs text-slate-400">
                            Distancia Interpupilar (DP): <strong class="text-white">{{ $screening->interpupillary_distance_mm }} mm</strong>
                        </div>
                    @endif
                </div>

                <!-- Recomendaciones Clínicas -->
                <div class="mt-6 p-4 rounded-2xl bg-indigo-950/40 border border-indigo-800/40 text-xs text-indigo-200">
                    <p class="font-bold text-indigo-300 uppercase tracking-wider mb-1">💡 Recomendación Personalizada:</p>
                    <p class="leading-relaxed">{{ $screening->recommendations }}</p>
                </div>

                <!-- Botones de Acción -->
                <div class="mt-8 flex flex-col sm:flex-row gap-3">
                    <a
                        href="{{ route('report.download', ['uuid' => $screening->uuid]) }}"
                        class="flex-1 py-3 px-4 rounded-xl bg-slate-800 hover:bg-slate-700 text-white font-bold text-xs uppercase tracking-wider text-center border border-slate-700 transition flex items-center justify-center gap-2"
                    >
                        <span>Descargar PDF Original (SpotVision)</span>
                        <span>📄</span>
                    </a>

                    <a
                        href="https://api.whatsapp.com/send?phone=528180000000&text={{ urlencode('Hola, tengo dudas sobre mi reporte de tamizaje visual con folio ' . ($screening->subject_code ?: $client->full_name)) }}"
                        target="_blank"
                        class="flex-1 py-3 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs uppercase tracking-wider text-center transition shadow-lg shadow-emerald-600/20 flex items-center justify-center gap-2"
                    >
                        <span>Contactar Optometrista</span>
                        <span>💬</span>
                    </a>
                </div>
            </div>

            <!-- Aviso Médico Legal -->
            <p class="text-center text-[11px] text-slate-500 leading-relaxed px-4">
                El cribado de visión no exime de acudir a un oftalmólogo u optometrista para someterse a un examen ocular completo. Realizado con tecnología Welch Allyn Spot™ Vision Screener.
            </p>
        </div>
    </main>

    <footer class="py-4 text-center text-xs text-slate-600 border-t border-slate-900">
        © {{ date('Y') }} Visual Corporativo • Salud y Seguridad Visual en el Trabajo
    </footer>
</body>
</html>
