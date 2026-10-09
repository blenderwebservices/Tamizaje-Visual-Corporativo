<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Tamizaje Visual | {{ $client->full_name }}</title>
    <!-- Google Fonts: DM Sans & Inter (Estilo Telemedicina) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400..800;1,9..40,400..800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        heading: ['"DM Sans"', 'sans-serif'],
                    },
                    colors: {
                        tele: {
                            primary: '#4f8cff',
                            secondary: '#00c9a7',
                            bg: '#f8fafc',
                            card: '#ffffff',
                            dark: '#1a365d',
                            text: '#1e293b',
                            muted: '#64748b',
                            border: '#e2e8f0',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        :root {
            --color-primary: #4f8cff;
            --color-secondary: #00c9a7;
            --color-bg: #f8fafc;
            --color-bg-alt: #eef4ff;
            --color-bg-card: #ffffff;
            --color-bg-dark: #1a365d;
            --color-text: #1e293b;
            --color-text-muted: #64748b;
            --color-border: #e2e8f0;
            --font-heading: "DM Sans", sans-serif;
            --font-body: "Inter", sans-serif;
        }

        body {
            font-family: var(--font-body);
            background-color: var(--color-bg);
            color: var(--color-text);
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: var(--font-heading);
            font-weight: 700;
        }

        .gradient-text {
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .soft-card {
            background: var(--color-bg-card);
            border-radius: 28px;
            border: 1px solid var(--color-border);
            box-shadow: 8px 8px 30px rgba(148, 163, 184, 0.14), -8px -8px 30px rgba(255, 255, 255, 0.9);
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--color-primary) 0%, #6ba3ff 100%);
            color: #ffffff;
            cursor: pointer;
            border: none;
            border-radius: 14px;
            padding: 12px 24px;
            font-weight: 700;
            transition: all 0.3s ease;
            box-shadow: 0 4px 14px rgba(79, 140, 255, 0.3);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(79, 140, 255, 0.45);
        }

        .btn-secondary {
            background: var(--color-bg-card);
            color: var(--color-primary);
            border: 2px solid var(--color-primary);
            cursor: pointer;
            border-radius: 14px;
            padding: 12px 24px;
            font-weight: 700;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-secondary:hover {
            background: var(--color-primary);
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(79, 140, 255, 0.25);
        }

        .badge-primary {
            color: var(--color-primary);
            background: rgba(79, 140, 255, 0.1);
            border: 1px solid rgba(79, 140, 255, 0.25);
            border-radius: 50px;
            padding: 4px 12px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-flex;
        }

        .badge-secondary {
            color: var(--color-secondary);
            background: rgba(0, 201, 167, 0.1);
            border: 1px solid rgba(0, 201, 167, 0.25);
            border-radius: 50px;
            padding: 4px 12px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-flex;
        }

        .navbar-tele {
            background: rgba(248, 250, 252, 0.88);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--color-border);
        }
    </style>
</head>
<body class="telemedicine min-h-screen flex flex-col justify-between antialiased selection:bg-[#4f8cff] selection:text-white relative">

    <!-- Halos de luz ambiental estilo Telemedicina -->
    <div class="fixed inset-0 pointer-events-none z-0 overflow-hidden">
        <div class="absolute top-10 right-10 w-[500px] h-[500px] rounded-full bg-[var(--color-primary)]/10 blur-3xl"></div>
        <div class="absolute bottom-10 left-10 w-[450px] h-[450px] rounded-full bg-[var(--color-secondary)]/10 blur-3xl"></div>
    </div>

    <!-- Encabezado Clínico Telemedicina -->
    <header class="navbar-tele py-3.5 px-6 sticky top-0 z-50">
        <div class="max-w-2xl mx-auto flex items-center justify-between">
            <a href="/" class="flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-[var(--color-primary)] to-[var(--color-secondary)] flex items-center justify-center text-white text-xl shadow-md shadow-[#4f8cff]/20 group-hover:scale-105 transition-transform">
                    👁️
                </div>
                <div>
                    <h1 class="text-sm font-extrabold text-[var(--color-text)] leading-tight">Visual Corporativo</h1>
                    <p class="text-[11px] text-[var(--color-primary)] font-semibold">Reporte Oficial Spot™ Vision</p>
                </div>
            </a>
            <a
                href="{{ route('report.download', ['uuid' => $screening->uuid]) }}"
                class="btn-secondary text-xs !py-2 !px-4"
            >
                <span>Descargar PDF</span>
                <span>↓</span>
            </a>
        </div>
    </header>

    <!-- Cuerpo del Reporte -->
    <main class="flex-1 py-8 sm:py-12 px-4 flex justify-center relative z-10">
        <div class="w-full max-w-2xl space-y-6">

            <!-- Tarjeta de Paciente y Resultado General (Fase 2 y 3) -->
            <div class="soft-card p-6 sm:p-9 relative overflow-hidden bg-white">
                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 border-b border-[var(--color-border)] pb-6">
                    <div>
                        <span class="text-xs font-bold text-[var(--color-primary)] uppercase tracking-wider block mb-1">
                            Resultados del Examen
                        </span>
                        <h2 class="text-2xl sm:text-3xl font-extrabold text-[var(--color-text)] tracking-tight">
                            {{ $client->full_name }}
                        </h2>
                        <div class="text-xs text-[var(--color-text-muted)] mt-2 flex flex-wrap gap-x-4 gap-y-1">
                            <span>🏢 <strong>Empresa:</strong> {{ $company?->name ?: 'Jornada Corporativa' }}</span>
                            <span>📅 <strong>Fecha:</strong> {{ $screening->exam_date?->format('d/m/Y h:i a') }}</span>
                            @if($screening->subject_code)
                                <span>🏷️ <strong>ID Sujeto:</strong> <code class="font-bold text-[var(--color-primary)]">{{ $screening->subject_code }}</code></span>
                            @endif
                        </div>
                    </div>

                    <!-- Insignia de Resultado (PASA / REMITIR) -->
                    <div class="self-start">
                        @if($screening->screening_status === 'pass')
                            <div class="px-5 py-2.5 rounded-2xl bg-[#00c9a7]/15 border border-[#00c9a7]/30 text-[#008f75] text-center shadow-sm">
                                <span class="text-[11px] font-bold uppercase tracking-wider block">Resultado General</span>
                                <span class="text-lg font-extrabold flex items-center justify-center gap-1 mt-0.5">
                                    ✓ PASA
                                </span>
                            </div>
                        @else
                            <div class="px-5 py-2.5 rounded-2xl bg-[#ff9f43]/15 border border-[#ff9f43]/30 text-[#d97706] text-center shadow-sm">
                                <span class="text-[11px] font-bold uppercase tracking-wider block">Resultado General</span>
                                <span class="text-lg font-extrabold flex items-center justify-center gap-1 mt-0.5">
                                    ⚠️ REMITIR
                                </span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Análisis Rápido en Lenguaje Humano -->
                <div class="mt-6 p-5 rounded-2xl bg-[var(--color-bg-alt)] border border-[var(--color-border)]">
                    <h3 class="text-xs font-bold text-[var(--color-text)] uppercase tracking-wider mb-1.5 flex items-center gap-1.5">
                        <span class="text-base">📋</span> Análisis Rápido del Optometrista
                    </h3>
                    <p class="text-sm text-[var(--color-text)] leading-relaxed">
                        {{ $screening->quick_analysis_summary }}
                    </p>

                    @if(!empty($screening->findings) && is_array($screening->findings))
                        <div class="mt-3.5 flex flex-wrap items-center gap-2 pt-3 border-t border-[var(--color-border)]">
                            <span class="text-xs text-[var(--color-text-muted)] font-medium">Condición Detectada:</span>
                            @foreach($screening->findings as $finding)
                                <span class="badge-primary">
                                    {{ $finding }}
                                </span>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Mediciones Ópticas (OD vs OS) -->
                <div class="mt-6">
                    <h3 class="text-xs font-bold text-[var(--color-text-muted)] uppercase tracking-wider mb-3">
                        Mediciones Computarizadas de Autorrefracción
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Ojo Derecho (OD) -->
                        <div class="p-5 rounded-2xl bg-white border border-[var(--color-border)] shadow-sm hover:border-[var(--color-primary)] transition">
                            <div class="flex items-center justify-between mb-3 border-b border-[var(--color-border)] pb-2">
                                <span class="font-bold text-[#4f8cff] text-sm flex items-center gap-1">
                                    <span>👁️</span> Ojo Derecho (OD)
                                </span>
                                <span class="text-xs text-[var(--color-text-muted)]">Pupila: {{ $screening->od_pupil_size_mm ? $screening->od_pupil_size_mm . ' mm' : '-' }}</span>
                            </div>

                            <div class="grid grid-cols-3 gap-2 text-center">
                                <div class="p-2 rounded-xl bg-[var(--color-bg-alt)] border border-[var(--color-border)]">
                                    <span class="text-[10px] text-[var(--color-text-muted)] block font-semibold">Esfera (DS)</span>
                                    <span class="text-sm font-bold text-[var(--color-text)] font-mono">
                                        {{ $screening->od_sphere_ds !== null ? number_format($screening->od_sphere_ds, 2) : '-' }}
                                    </span>
                                </div>
                                <div class="p-2 rounded-xl bg-[var(--color-bg-alt)] border border-[var(--color-border)]">
                                    <span class="text-[10px] text-[var(--color-text-muted)] block font-semibold">Cilindro (DC)</span>
                                    <span class="text-sm font-bold text-[var(--color-text)] font-mono">
                                        {{ $screening->od_cylinder_dc !== null ? number_format($screening->od_cylinder_dc, 2) : '-' }}
                                    </span>
                                </div>
                                <div class="p-2 rounded-xl bg-[var(--color-bg-alt)] border border-[var(--color-border)]">
                                    <span class="text-[10px] text-[var(--color-text-muted)] block font-semibold">Eje</span>
                                    <span class="text-sm font-bold text-[var(--color-text)] font-mono">
                                        {{ $screening->od_axis !== null ? $screening->od_axis . '°' : '-' }}
                                    </span>
                                </div>
                            </div>

                            <div class="mt-3 text-[11px] text-[var(--color-text-muted)] flex justify-between">
                                <span>Eq. Esférico (SE): <strong class="text-[var(--color-text)]">{{ $screening->od_sphere_se !== null ? number_format($screening->od_sphere_se, 2) : '-' }}</strong></span>
                                <span>Alineación: <strong class="text-[var(--color-text)]">{{ $screening->od_gaze_v ?: '0°' }}</strong></span>
                            </div>
                        </div>

                        <!-- Ojo Izquierdo (OS) -->
                        <div class="p-5 rounded-2xl bg-white border border-[var(--color-border)] shadow-sm hover:border-[#8b5cf6] transition">
                            <div class="flex items-center justify-between mb-3 border-b border-[var(--color-border)] pb-2">
                                <span class="font-bold text-[#8b5cf6] text-sm flex items-center gap-1">
                                    <span>👁️</span> Ojo Izquierdo (OS)
                                </span>
                                <span class="text-xs text-[var(--color-text-muted)]">Pupila: {{ $screening->os_pupil_size_mm ? $screening->os_pupil_size_mm . ' mm' : '-' }}</span>
                            </div>

                            <div class="grid grid-cols-3 gap-2 text-center">
                                <div class="p-2 rounded-xl bg-[var(--color-bg-alt)] border border-[var(--color-border)]">
                                    <span class="text-[10px] text-[var(--color-text-muted)] block font-semibold">Esfera (DS)</span>
                                    <span class="text-sm font-bold text-[var(--color-text)] font-mono">
                                        {{ $screening->os_sphere_ds !== null ? number_format($screening->os_sphere_ds, 2) : '-' }}
                                    </span>
                                </div>
                                <div class="p-2 rounded-xl bg-[var(--color-bg-alt)] border border-[var(--color-border)]">
                                    <span class="text-[10px] text-[var(--color-text-muted)] block font-semibold">Cilindro (DC)</span>
                                    <span class="text-sm font-bold text-[var(--color-text)] font-mono">
                                        {{ $screening->os_cylinder_dc !== null ? number_format($screening->os_cylinder_dc, 2) : '-' }}
                                    </span>
                                </div>
                                <div class="p-2 rounded-xl bg-[var(--color-bg-alt)] border border-[var(--color-border)]">
                                    <span class="text-[10px] text-[var(--color-text-muted)] block font-semibold">Eje</span>
                                    <span class="text-sm font-bold text-[var(--color-text)] font-mono">
                                        {{ $screening->os_axis !== null ? $screening->os_axis . '°' : '-' }}
                                    </span>
                                </div>
                            </div>

                            <div class="mt-3 text-[11px] text-[var(--color-text-muted)] flex justify-between">
                                <span>Eq. Esférico (SE): <strong class="text-[var(--color-text)]">{{ $screening->os_sphere_se !== null ? number_format($screening->os_sphere_se, 2) : '-' }}</strong></span>
                                <span>Alineación: <strong class="text-[var(--color-text)]">{{ $screening->os_gaze_v ?: '0°' }}</strong></span>
                            </div>
                        </div>
                    </div>

                    @if($screening->interpupillary_distance_mm)
                        <div class="mt-3 text-center text-xs text-[var(--color-text-muted)]">
                            Distancia Interpupilar (DP): <strong class="text-[var(--color-text)]">{{ $screening->interpupillary_distance_mm }} mm</strong>
                        </div>
                    @endif
                </div>

                <!-- Recomendaciones Clínicas -->
                <div class="mt-6 p-4 rounded-2xl bg-[#4f8cff]/10 border border-[#4f8cff]/25 text-xs text-[var(--color-text)]">
                    <p class="font-bold text-[#4f8cff] uppercase tracking-wider mb-1 flex items-center gap-1.5">
                        <span>💡</span> Recomendación Personalizada:
                    </p>
                    <p class="leading-relaxed">{{ $screening->recommendations }}</p>
                </div>

                <!-- Botones de Acción -->
                <div class="mt-8 flex flex-col sm:flex-row gap-3">
                    <a
                        href="{{ route('report.download', ['uuid' => $screening->uuid]) }}"
                        class="btn-secondary flex-1 text-xs uppercase tracking-wider text-center"
                    >
                        <span>Descargar PDF Original (SpotVision)</span>
                        <span>📄</span>
                    </a>

                    <a
                        href="https://api.whatsapp.com/send?phone=528180000000&text={{ urlencode('Hola, tengo dudas sobre mi reporte de tamizaje visual con folio ' . ($screening->subject_code ?: $client->full_name)) }}"
                        target="_blank"
                        class="btn-primary flex-1 text-xs uppercase tracking-wider text-center shadow-lg"
                    >
                        <span>Contactar Optometrista</span>
                        <span>💬</span>
                    </a>
                </div>
            </div>

            <!-- Aviso Médico Legal -->
            <p class="text-center text-[11px] text-[var(--color-text-muted)] leading-relaxed px-4">
                El cribado de visión no exime de acudir a un oftalmólogo u optometrista para someterse a un examen ocular completo. Realizado con tecnología Welch Allyn Spot™ Vision Screener.
            </p>
        </div>
    </main>

    <footer class="py-4 text-center text-xs text-[var(--color-text-muted)] border-t border-[var(--color-border)] relative z-10 bg-white/70">
        © {{ date('Y') }} Visual Corporativo • Salud y Seguridad Visual en el Trabajo
    </footer>
</body>
</html>
