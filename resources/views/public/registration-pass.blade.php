<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pase de Tamizaje Visual | SpotVision</title>
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
            box-shadow: 8px 8px 32px rgba(148, 163, 184, 0.16), -8px -8px 32px rgba(255, 255, 255, 0.9);
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--color-primary) 0%, #6ba3ff 100%);
            color: #ffffff;
            cursor: pointer;
            border: none;
            border-radius: 16px;
            padding: 14px 28px;
            font-weight: 700;
            transition: all 0.3s ease;
            box-shadow: 0 4px 16px rgba(79, 140, 255, 0.3);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(79, 140, 255, 0.45);
        }

        .badge-secondary {
            color: var(--color-secondary);
            background: rgba(0, 201, 167, 0.1);
            border: 1px solid rgba(0, 201, 167, 0.25);
            border-radius: 50px;
            padding: 6px 14px;
            font-size: 0.8125rem;
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
        <div class="absolute top-10 right-10 w-[450px] h-[450px] rounded-full bg-[var(--color-primary)]/10 blur-3xl"></div>
        <div class="absolute bottom-10 left-10 w-[400px] h-[400px] rounded-full bg-[var(--color-secondary)]/10 blur-3xl"></div>
    </div>

    <header class="navbar-tele py-4 px-6 text-center sticky top-0 z-50">
        <div class="max-w-md mx-auto flex items-center justify-between">
            <a href="/" class="text-xs font-bold text-[var(--color-primary)] hover:underline flex items-center gap-1">
                ← Inicio
            </a>
            <h1 class="text-xs font-bold text-[var(--color-text-muted)] uppercase tracking-widest">
                Pase de Acceso a Tamizaje
            </h1>
            <div class="w-12"></div>
        </div>
    </header>

    <main class="flex-1 py-8 sm:py-12 px-4 flex items-center justify-center relative z-10">
        <div class="w-full max-w-md soft-card p-6 sm:p-9 text-center relative overflow-hidden bg-white">
            <!-- Icono de Confirmación Telemedicina -->
            <div class="w-16 h-16 bg-[#00c9a7]/15 border border-[#00c9a7]/30 text-[#00c9a7] rounded-2xl mx-auto flex items-center justify-center text-3xl mb-4 shadow-lg shadow-[#00c9a7]/10">
                ✓
            </div>

            <h2 class="text-2xl sm:text-3xl font-extrabold text-[var(--color-text)] tracking-tight">¡Registro Confirmado!</h2>
            <p class="text-xs sm:text-sm text-[var(--color-text-muted)] mt-1.5 leading-relaxed">
                Acércate al módulo del optometrista para tu examen visual en el stand corporativo.
            </p>

            <!-- Tarjeta de Identificación para el SpotVision -->
            <div class="mt-6 p-6 rounded-2xl bg-[var(--color-bg-alt)] border border-[var(--color-border)] shadow-sm">
                <span class="text-[11px] font-bold text-[var(--color-text-muted)] uppercase tracking-wider block">
                    Tu Clave de Examen (ID Sujeto)
                </span>
                <p class="text-4xl font-extrabold text-[var(--color-primary)] font-mono tracking-widest my-2.5">
                    {{ $client->subject_code ?: $client->client_code }}
                </p>

                <!-- Gráfico de Simulación QR para Escaneo Ágil -->
                <div class="w-36 h-36 bg-white p-2.5 rounded-2xl mx-auto my-3 shadow-sm border border-[var(--color-border)] flex items-center justify-center">
                    <svg viewBox="0 0 100 100" class="w-full h-full text-[var(--color-text)] fill-current">
                        <!-- Generador de patrón QR representativo -->
                        <path d="M0,0 h30 v30 h-30 z M10,10 h10 v10 h-10 z M70,0 h30 v30 h-30 z M80,10 h10 v10 h-10 z M0,70 h30 v30 h-30 z M10,80 h10 v10 h-10 z M40,10 h20 v10 h-20 z M10,40 h20 v20 h-20 z M40,40 h20 v20 h-20 z M70,40 h20 v20 h-20 z M40,70 h20 v20 h-20 z M70,70 h30 v10 h-30 z M80,85 h20 v15 h-20 z" />
                    </svg>
                </div>

                <div class="text-left mt-4 pt-4 border-t border-[var(--color-border)] text-xs text-[var(--color-text)] space-y-1.5">
                    <p class="flex justify-between">
                        <span class="text-[var(--color-text-muted)]">Colaborador:</span>
                        <strong class="text-right">{{ $client->full_name }}</strong>
                    </p>
                    <p class="flex justify-between">
                        <span class="text-[var(--color-text-muted)]">Empresa:</span>
                        <strong class="text-right">{{ $client->company?->name ?: 'Jornada Corporativa' }}</strong>
                    </p>
                    <p class="flex justify-between">
                        <span class="text-[var(--color-text-muted)]">WhatsApp:</span>
                        <strong class="text-right">{{ $client->phone }}</strong>
                    </p>
                </div>
            </div>

            <div class="mt-6 p-4 rounded-2xl bg-[#00c9a7]/10 border border-[#00c9a7]/25 text-[#008f75] text-xs text-left flex items-start gap-3">
                <span class="text-xl leading-none">⏱️</span>
                <p class="leading-relaxed">El escaneo toma solo <strong>10 segundos</strong> a 1 metro de distancia con luces y sonidos guía del equipo SpotVision.</p>
            </div>

            @if($client->latestScreening)
                <a
                    href="{{ $client->latestScreening->public_report_url }}"
                    class="btn-primary w-full mt-5 text-xs uppercase tracking-wider shadow-md"
                >
                    <span>Ver Mi Reporte Visual Completo</span>
                    <span>→</span>
                </a>
            @endif
        </div>
    </main>

    <footer class="py-4 text-center text-xs text-[var(--color-text-muted)] border-t border-[var(--color-border)] relative z-10 bg-white/70">
        Visual Corporativo • Cuidado Visual en el Entorno Laboral
    </footer>
</body>
</html>
