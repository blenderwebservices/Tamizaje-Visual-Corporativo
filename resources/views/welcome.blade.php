<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tamizaje Visual Corporativo | SpotVision Hub</title>
    <!-- Google Fonts: DM Sans para títulos e Inter para textos (Estilo Telemedicina) -->
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
                            primaryHover: '#3b78eb',
                            secondary: '#00c9a7',
                            secondaryHover: '#00b395',
                            accent: '#ff6b9d',
                            purple: '#8b5cf6',
                            orange: '#ff9f43',
                            cyan: '#00d4ff',
                            bg: '#f8fafc',
                            bgAlt: '#eef4ff',
                            card: '#ffffff',
                            dark: '#1a365d',
                            text: '#1e293b',
                            muted: '#64748b',
                            subtle: '#94a3b8',
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
            --color-accent: #ff6b9d;
            --color-purple: #8b5cf6;
            --color-orange: #ff9f43;
            --color-cyan: #00d4ff;
            --color-bg: #f8fafc;
            --color-bg-alt: #eef4ff;
            --color-bg-card: #ffffff;
            --color-bg-dark: #1a365d;
            --color-text: #1e293b;
            --color-text-muted: #64748b;
            --color-text-subtle: #94a3b8;
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
            border-radius: 24px;
            border: 1px solid var(--color-border);
            box-shadow: 8px 8px 24px rgba(148, 163, 184, 0.12), -8px -8px 24px rgba(255, 255, 255, 0.9);
            transition: all 0.3s ease;
        }

        .soft-card:hover {
            box-shadow: 12px 12px 32px rgba(148, 163, 184, 0.18), -12px -12px 32px rgba(255, 255, 255, 1);
            transform: translateY(-2px);
        }

        .feature-card {
            background: var(--color-bg-card);
            border: 1px solid var(--color-border);
            border-radius: 24px;
            padding: 24px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 20px rgba(148, 163, 184, 0.08);
        }

        .feature-card:hover {
            border-color: var(--color-primary);
            transform: translateY(-4px);
            box-shadow: 0 16px 40px rgba(79, 140, 255, 0.15);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--color-primary) 0%, #6ba3ff 100%);
            color: #ffffff;
            cursor: pointer;
            border: none;
            border-radius: 14px;
            padding: 12px 24px;
            font-weight: 600;
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
            font-weight: 600;
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

        .badge {
            border-radius: 50px;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            font-size: 0.8125rem;
            font-weight: 600;
            display: inline-flex;
        }

        .badge-primary {
            color: var(--color-primary);
            background: rgba(79, 140, 255, 0.1);
            border: 1px solid rgba(79, 140, 255, 0.25);
        }

        .badge-secondary {
            color: var(--color-secondary);
            background: rgba(0, 201, 167, 0.1);
            border: 1px solid rgba(0, 201, 167, 0.25);
        }

        .stat-number {
            font-family: var(--font-heading);
            color: var(--color-primary);
            font-size: 2.25rem;
            font-weight: 700;
            line-height: 1;
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

    <!-- Iluminación y halos ambientales estilo Telemedicina -->
    <div class="fixed inset-0 pointer-events-none z-0 overflow-hidden">
        <div class="absolute top-12 right-10 w-[500px] h-[500px] rounded-full bg-[var(--color-primary)]/10 blur-3xl"></div>
        <div class="absolute top-1/3 left-6 w-[450px] h-[450px] rounded-full bg-[var(--color-secondary)]/10 blur-3xl"></div>
        <div class="absolute bottom-32 right-1/4 w-[450px] h-[450px] rounded-full bg-[var(--color-primary)]/8 blur-3xl"></div>
    </div>

    <!-- Header de Navegación estilo Telemedicina -->
    <header class="navbar-tele sticky top-0 z-50 py-3.5 px-6">
        <div class="max-w-6xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-[var(--color-primary)] to-[var(--color-secondary)] flex items-center justify-center text-white text-xl shadow-lg shadow-[#4f8cff]/20">
                    👁️
                </div>
                <div>
                    <h1 class="text-base font-extrabold text-[var(--color-text)] tracking-tight leading-none">Visual Corporativo</h1>
                    <p class="text-xs text-[var(--color-primary)] font-semibold mt-0.5">De la Atracción al Retargeting • SpotVision</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a
                    href="{{ route('registration.form') }}"
                    class="hidden sm:inline-flex px-4 py-2 text-xs font-bold text-[var(--color-text)] hover:text-[var(--color-primary)] rounded-xl bg-white hover:bg-slate-50 transition border border-[var(--color-border)] shadow-sm"
                >
                    Registro Móvil (Fase 1)
                </a>
                <a
                    href="/admin"
                    class="btn-primary text-xs !py-2.5 !px-5 uppercase tracking-wider"
                >
                    Panel Filament →
                </a>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <main class="flex-1 py-12 sm:py-16 px-6 relative z-10">
        <div class="max-w-6xl mx-auto space-y-16">

            <!-- Encabezado Principal -->
            <div class="text-center max-w-3xl mx-auto space-y-5">
                <div class="badge badge-primary">
                    <span class="w-2 h-2 rounded-full bg-[var(--color-primary)] animate-pulse"></span>
                    <span>✨ Solución Integral Automatizada • Welch Allyn Spot Vision</span>
                </div>
                <h2 class="text-3xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight leading-tight">
                    <span class="gradient-text">Flujo de Tamizaje Visual Corporativo</span>
                </h2>
                <p class="text-base sm:text-lg text-[var(--color-text-muted)] leading-relaxed">
                    Plataforma conectada con autorrefractómetros <strong>Welch Allyn Spot Vision Screener</strong> para importar archivos PDF desde USB, deduplicar pacientes, extraer datos clínicos mediante IA y programar envíos y retargeting vía WhatsApp.
                </p>

                <div class="pt-4 flex flex-wrap items-center justify-center gap-4">
                    <a
                        href="/admin"
                        class="btn-primary text-sm sm:text-base font-bold shadow-lg"
                    >
                        <span>Entrar al Panel de Control (Filament)</span>
                        <span>🚀</span>
                    </a>
                    <a
                        href="{{ route('registration.form') }}"
                        class="btn-secondary text-sm sm:text-base font-bold shadow-sm"
                    >
                        <span>Simular Registro de Paciente en Celular</span>
                        <span>📱</span>
                    </a>
                </div>

                <!-- Credenciales Rápidas para Demostración -->
                <div class="soft-card p-4 sm:p-5 text-xs text-[var(--color-text-muted)] max-w-md mx-auto mt-4 bg-white/95">
                    <div class="flex items-center justify-between pb-2 mb-2 border-b border-[var(--color-border)]">
                        <span class="font-bold text-[var(--color-text)] flex items-center gap-1.5">
                            <span class="text-base">🔐</span> Acceso al Panel Administrativo
                        </span>
                        <span class="text-[10px] uppercase font-bold text-[var(--color-primary)] bg-[var(--color-primary)]/10 px-2 py-0.5 rounded-full">
                            Credenciales Demo
                        </span>
                    </div>
                    <div class="space-y-1 text-left">
                        <p class="flex justify-between items-center">
                            <span>Usuario:</span>
                            <span class="text-[var(--color-primary)] font-mono font-bold">admin@visualcorporativo.com</span>
                        </p>
                        <p class="flex justify-between items-center">
                            <span>Contraseña:</span>
                            <span class="text-[var(--color-primary)] font-mono font-bold">admin123</span>
                        </p>
                    </div>
                </div>

                <!-- Barra de Estadísticas Rápidas estilo Telemedicina -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 pt-4 max-w-3xl mx-auto">
                    <div class="p-3.5 rounded-2xl bg-white border border-[var(--color-border)] shadow-sm">
                        <div class="stat-number">10s</div>
                        <div class="text-xs text-[var(--color-text-muted)] font-medium mt-1">Captura Móvil QR</div>
                    </div>
                    <div class="p-3.5 rounded-2xl bg-white border border-[var(--color-border)] shadow-sm">
                        <div class="stat-number">5 seg</div>
                        <div class="text-xs text-[var(--color-text-muted)] font-medium mt-1">Examen Binocular</div>
                    </div>
                    <div class="p-3.5 rounded-2xl bg-white border border-[var(--color-border)] shadow-sm">
                        <div class="stat-number">100%</div>
                        <div class="text-xs text-[var(--color-text-muted)] font-medium mt-1">Deduplicación Auto</div>
                    </div>
                    <div class="p-3.5 rounded-2xl bg-white border border-[var(--color-border)] shadow-sm">
                        <div class="stat-number">Día 90</div>
                        <div class="text-xs text-[var(--color-text-muted)] font-medium mt-1">Drip Retargeting</div>
                    </div>
                </div>
            </div>

            <!-- Sección de Metodología y Timeline Vertical de 5 Fases -->
            <div class="space-y-12">
                <!-- Encabezado de la sección -->
                <div class="text-center max-w-2xl mx-auto space-y-3">
                    <div class="badge badge-secondary">
                        <span class="w-2 h-2 rounded-full bg-[var(--color-secondary)] animate-pulse"></span>
                        <span>Flujo Operativo End-to-End</span>
                    </div>
                    <h3 class="text-2xl sm:text-4xl font-extrabold text-[var(--color-text)] tracking-tight">
                        Metodología en <span class="gradient-text">5 Fases Clínicas</span>
                    </h3>
                    <p class="text-sm sm:text-base text-[var(--color-text-muted)]">
                        Un recorrido integral y automatizado desde el primer registro del colaborador en el corporativo hasta la fidelización y retargeting inteligente.
                    </p>
                </div>

                <!-- Timeline Vertical Responsivo -->
                <div class="relative max-w-5xl mx-auto pt-4 pb-8">
                    <!-- Línea Central del Timeline con Gradiente Luminoso -->
                    <div class="absolute left-6 md:left-1/2 top-6 bottom-6 w-1 -translate-x-1/2 bg-gradient-to-b from-[#4f8cff] via-[#00c9a7] via-[#00d4ff] via-[#ff9f43] to-[#8b5cf6] rounded-full shadow-[0_0_15px_rgba(79,140,255,0.3)]"></div>

                    <div class="space-y-12 md:space-y-16">
                        <!-- FASE 1: Atracción y Captura (Izquierda en Desktop) -->
                        <div class="relative flex flex-col md:flex-row items-start md:items-center group">
                            <!-- Card (Desktop Izquierda) -->
                            <div class="w-full md:w-1/2 pl-16 md:pl-0 md:pr-12">
                                <div class="feature-card bg-white relative overflow-hidden transition-all duration-300 hover:border-[#4f8cff] hover:shadow-xl hover:shadow-[#4f8cff]/10 hover:-translate-y-1">
                                    <div class="absolute top-0 right-0 w-32 h-32 bg-[#4f8cff]/10 rounded-full blur-2xl pointer-events-none group-hover:bg-[#4f8cff]/15 transition-all"></div>
                                    <div class="flex items-center justify-between gap-3 mb-3">
                                        <span class="px-3 py-1 rounded-full text-[11px] font-black uppercase tracking-wider bg-[#4f8cff]/10 border border-[#4f8cff]/25 text-[#4f8cff]">
                                            Fase 01 • En Sitio & Móvil
                                        </span>
                                        <span class="text-xs font-semibold text-[var(--color-text-muted)] flex items-center gap-1">
                                            ⏱️ ~10 seg
                                        </span>
                                    </div>
                                    <h4 class="text-lg sm:text-xl font-bold text-[var(--color-text)] group-hover:text-[#4f8cff] transition-colors">
                                        Atracción y Captura Ágil
                                    </h4>
                                    <p class="text-xs sm:text-sm text-[var(--color-text-muted)] mt-2 leading-relaxed">
                                        Registro ultrarrápido desde el smartphone del colaborador mediante escaneo de código QR en el stand corporativo, eliminando filas y capturas manuales de datos.
                                    </p>

                                    <!-- Infografía Ilustrativa Fase 1 -->
                                    <div class="mt-4 mb-3 overflow-hidden rounded-2xl bg-slate-50 border border-[var(--color-border)] shadow-sm p-2.5 sm:p-3 group/img cursor-pointer transition-all duration-300 hover:border-[#4f8cff] hover:shadow-md" onclick="openPhaseModal('{{ asset('images/phases/fase1.png') }}', 'Fase 01: Atracción y Captura de Datos (El Intercambio)')">
                                        <div class="relative overflow-hidden rounded-xl bg-white flex items-center justify-center p-1">
                                            <img
                                                src="{{ asset('images/phases/fase1.png') }}"
                                                alt="Ilustración Fase 1: Atracción y Captura de Datos"
                                                class="w-full max-h-56 sm:max-h-64 object-contain transition-transform duration-300 group-hover/img:scale-105"
                                                loading="lazy"
                                            >
                                            <div class="absolute inset-0 bg-slate-900/0 group-hover/img:bg-slate-900/15 transition-all flex items-end justify-end p-2 pointer-events-none">
                                                <span class="opacity-0 group-hover/img:opacity-100 transition-opacity px-2.5 py-1 rounded-lg bg-slate-900/90 text-[10px] font-bold text-white shadow-md flex items-center gap-1 backdrop-blur-sm">
                                                    🔍 Ampliar diagrama
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-4 pt-4 border-t border-[var(--color-border)] space-y-2 text-xs text-[var(--color-text)]">
                                        <div class="flex items-center gap-2">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#4f8cff] shrink-0"></span>
                                            <span>Formulario móvil ligero con validación de teléfono WhatsApp.</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#4f8cff] shrink-0"></span>
                                            <span>Consentimiento informado y aviso de privacidad legalmente garantizados.</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#4f8cff] shrink-0"></span>
                                            <span>Emisión de Pase Digital con UUID único y código de barras.</span>
                                        </div>
                                    </div>
                                    <div class="mt-5 pt-3 flex items-center justify-between">
                                        <a
                                            href="{{ route('registration.form') }}"
                                            class="inline-flex items-center gap-2 text-xs font-bold text-[#4f8cff] hover:text-[#3b78eb] transition group/btn"
                                        >
                                            <span>Simular Registro Móvil</span>
                                            <span class="group-hover/btn:translate-x-1 transition-transform">→</span>
                                        </a>
                                        <span class="text-xl">📱</span>
                                    </div>
                                </div>
                            </div>
                            <!-- Nodo Central -->
                            <div class="absolute left-6 md:left-1/2 -translate-x-1/2 top-6 md:top-1/2 md:-translate-y-1/2 z-10 flex items-center justify-center">
                                <div class="w-12 h-12 rounded-2xl bg-white border-2 border-[#4f8cff] shadow-lg shadow-[#4f8cff]/30 flex items-center justify-center text-lg text-[#4f8cff] font-black group-hover:scale-110 transition-transform">
                                    01
                                </div>
                            </div>
                            <!-- Espaciador Desktop Derecha -->
                            <div class="hidden md:block md:w-1/2"></div>
                        </div>

                        <!-- FASE 2: Tamizaje SpotVision (Derecha en Desktop) -->
                        <div class="relative flex flex-col md:flex-row items-start md:items-center group">
                            <!-- Espaciador Desktop Izquierda -->
                            <div class="hidden md:block md:w-1/2"></div>
                            <!-- Nodo Central -->
                            <div class="absolute left-6 md:left-1/2 -translate-x-1/2 top-6 md:top-1/2 md:-translate-y-1/2 z-10 flex items-center justify-center">
                                <div class="w-12 h-12 rounded-2xl bg-white border-2 border-[#00c9a7] shadow-lg shadow-[#00c9a7]/30 flex items-center justify-center text-lg text-[#00c9a7] font-black group-hover:scale-110 transition-transform">
                                    02
                                </div>
                            </div>
                            <!-- Card (Desktop Derecha) -->
                            <div class="w-full md:w-1/2 pl-16 md:pl-12 md:pr-0">
                                <div class="feature-card bg-white relative overflow-hidden transition-all duration-300 hover:border-[#00c9a7] hover:shadow-xl hover:shadow-[#00c9a7]/10 hover:-translate-y-1">
                                    <div class="absolute top-0 right-0 w-32 h-32 bg-[#00c9a7]/10 rounded-full blur-2xl pointer-events-none group-hover:bg-[#00c9a7]/15 transition-all"></div>
                                    <div class="flex items-center justify-between gap-3 mb-3">
                                        <span class="px-3 py-1 rounded-full text-[11px] font-black uppercase tracking-wider bg-[#00c9a7]/10 border border-[#00c9a7]/25 text-[#00c9a7]">
                                            Fase 02 • Equipamiento Médico
                                        </span>
                                        <span class="text-xs font-semibold text-[var(--color-text-muted)] flex items-center gap-1">
                                            ⏱️ 5 seg / ojo
                                        </span>
                                    </div>
                                    <h4 class="text-lg sm:text-xl font-bold text-[var(--color-text)] group-hover:text-[#00c9a7] transition-colors">
                                        Tamizaje y Extracción SpotVision
                                    </h4>
                                    <p class="text-xs sm:text-sm text-[var(--color-text-muted)] mt-2 leading-relaxed">
                                        Evaluación refractiva binocular con autorrefractómetro Welch Allyn Spot Vision Screener. Carga por USB de PDFs y análisis óptico automatizado.
                                    </p>

                                    <!-- Infografía Ilustrativa Fase 2 -->
                                    <div class="mt-4 mb-3 overflow-hidden rounded-2xl bg-slate-50 border border-[var(--color-border)] shadow-sm p-2.5 sm:p-3 group/img cursor-pointer transition-all duration-300 hover:border-[#00c9a7] hover:shadow-md" onclick="openPhaseModal('{{ asset('images/phases/fase2.png') }}', 'Fase 02: Tamizaje Visual con Spot Vision (La Experiencia)')">
                                        <div class="relative overflow-hidden rounded-xl bg-white flex items-center justify-center p-1">
                                            <img
                                                src="{{ asset('images/phases/fase2.png') }}"
                                                alt="Ilustración Fase 2: Tamizaje Visual con Spot Vision"
                                                class="w-full max-h-56 sm:max-h-64 object-contain transition-transform duration-300 group-hover/img:scale-105"
                                                loading="lazy"
                                            >
                                            <div class="absolute inset-0 bg-slate-900/0 group-hover/img:bg-slate-900/15 transition-all flex items-end justify-end p-2 pointer-events-none">
                                                <span class="opacity-0 group-hover/img:opacity-100 transition-opacity px-2.5 py-1 rounded-lg bg-slate-900/90 text-[10px] font-bold text-white shadow-md flex items-center gap-1 backdrop-blur-sm">
                                                    🔍 Ampliar diagrama
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-4 pt-4 border-t border-[var(--color-border)] space-y-2 text-xs text-[var(--color-text)]">
                                        <div class="flex items-center gap-2">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#00c9a7] shrink-0"></span>
                                            <span>Medición precisa de Esfera, Cilindro, Eje y Distancia Pupilar (OD/OS).</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#00c9a7] shrink-0"></span>
                                            <span>Extracción y recorte automático de la gráfica/imagen del reporte impreso.</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#00c9a7] shrink-0"></span>
                                            <span>Deduplicación inteligente por Nombre, Folio e ID Sujeto.</span>
                                        </div>
                                    </div>
                                    <div class="mt-5 pt-3 flex items-center justify-between">
                                        <a
                                            href="/admin/screenings"
                                            class="inline-flex items-center gap-2 text-xs font-bold text-[#00c9a7] hover:text-[#00b395] transition group/btn"
                                        >
                                            <span>Ver Tamizajes Clínicos</span>
                                            <span class="group-hover/btn:translate-x-1 transition-transform">→</span>
                                        </a>
                                        <span class="text-xl">🔬</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- FASE 3: Entrega Digital (Izquierda en Desktop) -->
                        <div class="relative flex flex-col md:flex-row items-start md:items-center group">
                            <!-- Card (Desktop Izquierda) -->
                            <div class="w-full md:w-1/2 pl-16 md:pl-0 md:pr-12">
                                <div class="feature-card bg-white relative overflow-hidden transition-all duration-300 hover:border-[#00d4ff] hover:shadow-xl hover:shadow-[#00d4ff]/10 hover:-translate-y-1">
                                    <div class="absolute top-0 right-0 w-32 h-32 bg-[#00d4ff]/10 rounded-full blur-2xl pointer-events-none group-hover:bg-[#00d4ff]/15 transition-all"></div>
                                    <div class="flex items-center justify-between gap-3 mb-3">
                                        <span class="px-3 py-1 rounded-full text-[11px] font-black uppercase tracking-wider bg-[#00d4ff]/10 border border-[#00d4ff]/25 text-[#0099cc]">
                                            Fase 03 • Comunicación Inmediata
                                        </span>
                                        <span class="text-xs font-semibold text-[var(--color-text-muted)] flex items-center gap-1">
                                            ⚡ Instantáneo
                                        </span>
                                    </div>
                                    <h4 class="text-lg sm:text-xl font-bold text-[var(--color-text)] group-hover:text-[#0099cc] transition-colors">
                                        Entrega Digital y Reporte WhatsApp
                                    </h4>
                                    <p class="text-xs sm:text-sm text-[var(--color-text-muted)] mt-2 leading-relaxed">
                                        Análisis clínico inmediato con semáforo de riesgo visual y despacho automático del reporte interactivo con enlace personalizado a WhatsApp en un clic.
                                    </p>

                                    <!-- Infografía Ilustrativa Fase 3 -->
                                    <div class="mt-4 mb-3 overflow-hidden rounded-2xl bg-slate-50 border border-[var(--color-border)] shadow-sm p-2.5 sm:p-3 group/img cursor-pointer transition-all duration-300 hover:border-[#00d4ff] hover:shadow-md" onclick="openPhaseModal('{{ asset('images/phases/fase3.png') }}', 'Fase 03: Entrega Digital y Asesoría Breve (El Gancho)')">
                                        <div class="relative overflow-hidden rounded-xl bg-white flex items-center justify-center p-1">
                                            <img
                                                src="{{ asset('images/phases/fase3.png') }}"
                                                alt="Ilustración Fase 3: Entrega Digital y Asesoría Breve"
                                                class="w-full max-h-56 sm:max-h-64 object-contain transition-transform duration-300 group-hover/img:scale-105"
                                                loading="lazy"
                                            >
                                            <div class="absolute inset-0 bg-slate-900/0 group-hover/img:bg-slate-900/15 transition-all flex items-end justify-end p-2 pointer-events-none">
                                                <span class="opacity-0 group-hover/img:opacity-100 transition-opacity px-2.5 py-1 rounded-lg bg-slate-900/90 text-[10px] font-bold text-white shadow-md flex items-center gap-1 backdrop-blur-sm">
                                                    🔍 Ampliar diagrama
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-4 pt-4 border-t border-[var(--color-border)] space-y-2 text-xs text-[var(--color-text)]">
                                        <div class="flex items-center gap-2">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#00d4ff] shrink-0"></span>
                                            <span>Semáforo clínico tripartito: Normal, Sospechoso o Crítico.</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#00d4ff] shrink-0"></span>
                                            <span>Notificación WhatsApp con enlace seguro para el paciente.</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#00d4ff] shrink-0"></span>
                                            <span>Visualizador web responsivo con opción de descarga de PDF.</span>
                                        </div>
                                    </div>
                                    <div class="mt-5 pt-3 flex items-center justify-between">
                                        <a
                                            href="/admin/whats-app-queue-page"
                                            class="inline-flex items-center gap-2 text-xs font-bold text-[#0099cc] hover:text-[#007a99] transition group/btn"
                                        >
                                            <span>Bandeja de Envíos WhatsApp</span>
                                            <span class="group-hover/btn:translate-x-1 transition-transform">→</span>
                                        </a>
                                        <span class="text-xl">💬</span>
                                    </div>
                                </div>
                            </div>
                            <!-- Nodo Central -->
                            <div class="absolute left-6 md:left-1/2 -translate-x-1/2 top-6 md:top-1/2 md:-translate-y-1/2 z-10 flex items-center justify-center">
                                <div class="w-12 h-12 rounded-2xl bg-white border-2 border-[#00d4ff] shadow-lg shadow-[#00d4ff]/30 flex items-center justify-center text-lg text-[#0099cc] font-black group-hover:scale-110 transition-transform">
                                    03
                                </div>
                            </div>
                            <!-- Espaciador Desktop Derecha -->
                            <div class="hidden md:block md:w-1/2"></div>
                        </div>

                        <!-- FASE 4: Cierre en Sitio CRM (Derecha en Desktop) -->
                        <div class="relative flex flex-col md:flex-row items-start md:items-center group">
                            <!-- Espaciador Desktop Izquierda -->
                            <div class="hidden md:block md:w-1/2"></div>
                            <!-- Nodo Central -->
                            <div class="absolute left-6 md:left-1/2 -translate-x-1/2 top-6 md:top-1/2 md:-translate-y-1/2 z-10 flex items-center justify-center">
                                <div class="w-12 h-12 rounded-2xl bg-white border-2 border-[#ff9f43] shadow-lg shadow-[#ff9f43]/30 flex items-center justify-center text-lg text-[#ff9f43] font-black group-hover:scale-110 transition-transform">
                                    04
                                </div>
                            </div>
                            <!-- Card (Desktop Derecha) -->
                            <div class="w-full md:w-1/2 pl-16 md:pl-12 md:pr-0">
                                <div class="feature-card bg-white relative overflow-hidden transition-all duration-300 hover:border-[#ff9f43] hover:shadow-xl hover:shadow-[#ff9f43]/10 hover:-translate-y-1">
                                    <div class="absolute top-0 right-0 w-32 h-32 bg-[#ff9f43]/10 rounded-full blur-2xl pointer-events-none group-hover:bg-[#ff9f43]/15 transition-all"></div>
                                    <div class="flex items-center justify-between gap-3 mb-3">
                                        <span class="px-3 py-1 rounded-full text-[11px] font-black uppercase tracking-wider bg-[#ff9f43]/10 border border-[#ff9f43]/25 text-[#d97706]">
                                            Fase 04 • Conversión Comercial
                                        </span>
                                        <span class="text-xs font-semibold text-[var(--color-text-muted)] flex items-center gap-1">
                                            🎯 Stand en Sitio
                                        </span>
                                    </div>
                                    <h4 class="text-lg sm:text-xl font-bold text-[var(--color-text)] group-hover:text-[#d97706] transition-colors">
                                        Cierre en Sitio y Gestión CRM
                                    </h4>
                                    <p class="text-xs sm:text-sm text-[var(--color-text-muted)] mt-2 leading-relaxed">
                                        Canalización inmediata de colaboradores con alteraciones visuales. Selección de armazones oftálmicos, micas con filtro azul o lentes de seguridad graduados.
                                    </p>

                                    <!-- Infografía Ilustrativa Fase 4 -->
                                    <div class="mt-4 mb-3 overflow-hidden rounded-2xl bg-slate-50 border border-[var(--color-border)] shadow-sm p-2.5 sm:p-3 group/img cursor-pointer transition-all duration-300 hover:border-[#ff9f43] hover:shadow-md" onclick="openPhaseModal('{{ asset('images/phases/fase4.png') }}', 'Fase 04: Cierre en Sitio o Etiquetado en CRM (La Clasificación)')">
                                        <div class="relative overflow-hidden rounded-xl bg-white flex items-center justify-center p-1">
                                            <img
                                                src="{{ asset('images/phases/fase4.png') }}"
                                                alt="Ilustración Fase 4: Cierre en Sitio o Etiquetado en CRM"
                                                class="w-full max-h-56 sm:max-h-64 object-contain transition-transform duration-300 group-hover/img:scale-105"
                                                loading="lazy"
                                            >
                                            <div class="absolute inset-0 bg-slate-900/0 group-hover/img:bg-slate-900/15 transition-all flex items-end justify-end p-2 pointer-events-none">
                                                <span class="opacity-0 group-hover/img:opacity-100 transition-opacity px-2.5 py-1 rounded-lg bg-slate-900/90 text-[10px] font-bold text-white shadow-md flex items-center gap-1 backdrop-blur-sm">
                                                    🔍 Ampliar diagrama
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-4 pt-4 border-t border-[var(--color-border)] space-y-2 text-xs text-[var(--color-text)]">
                                        <div class="flex items-center gap-2">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#ff9f43] shrink-0"></span>
                                            <span>Generación de orden comercial directa en sitio (Cliente Activo).</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#ff9f43] shrink-0"></span>
                                            <span>Catálogo óptico de seguridad industrial con descuento vía nómina.</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#ff9f43] shrink-0"></span>
                                            <span>Refracción fina complementaria en el consultorio móvil.</span>
                                        </div>
                                    </div>
                                    <div class="mt-5 pt-3 flex items-center justify-between">
                                        <a
                                            href="/admin/orders"
                                            class="inline-flex items-center gap-2 text-xs font-bold text-[#d97706] hover:text-[#b45309] transition group/btn"
                                        >
                                            <span>Módulo de Órdenes CRM</span>
                                            <span class="group-hover/btn:translate-x-1 transition-transform">→</span>
                                        </a>
                                        <span class="text-xl">🛒</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- FASE 5: Retargeting y Automatización (Izquierda en Desktop) -->
                        <div class="relative flex flex-col md:flex-row items-start md:items-center group">
                            <!-- Card (Desktop Izquierda) -->
                            <div class="w-full md:w-1/2 pl-16 md:pl-0 md:pr-12">
                                <div class="feature-card bg-white relative overflow-hidden transition-all duration-300 hover:border-[#8b5cf6] hover:shadow-xl hover:shadow-[#8b5cf6]/10 hover:-translate-y-1">
                                    <div class="absolute top-0 right-0 w-32 h-32 bg-[#8b5cf6]/10 rounded-full blur-2xl pointer-events-none group-hover:bg-[#8b5cf6]/15 transition-all"></div>
                                    <div class="flex items-center justify-between gap-3 mb-3">
                                        <span class="px-3 py-1 rounded-full text-[11px] font-black uppercase tracking-wider bg-[#8b5cf6]/10 border border-[#8b5cf6]/25 text-[#8b5cf6]">
                                            Fase 05 • Fidelización Automatizada
                                        </span>
                                        <span class="text-xs font-semibold text-[var(--color-text-muted)] flex items-center gap-1">
                                            🔄 3 a 90 días
                                        </span>
                                    </div>
                                    <h4 class="text-lg sm:text-xl font-bold text-[var(--color-text)] group-hover:text-[#8b5cf6] transition-colors">
                                        Retargeting y Drip Automático
                                    </h4>
                                    <p class="text-xs sm:text-sm text-[var(--color-text-muted)] mt-2 leading-relaxed">
                                        Secuencia inteligente programada por WhatsApp para prospectos no compradores, reactivando el interés mediante promociones y recordatorios clínicos.
                                    </p>

                                    <!-- Infografía Ilustrativa Fase 5 -->
                                    <div class="mt-4 mb-3 overflow-hidden rounded-2xl bg-slate-50 border border-[var(--color-border)] shadow-sm p-2.5 sm:p-3 group/img cursor-pointer transition-all duration-300 hover:border-[#8b5cf6] hover:shadow-md" onclick="openPhaseModal('{{ asset('images/phases/fase5.png') }}', 'Fase 05: Retargeting y Automatización (El Seguimiento)')">
                                        <div class="relative overflow-hidden rounded-xl bg-white flex items-center justify-center p-1">
                                            <img
                                                src="{{ asset('images/phases/fase5.png') }}"
                                                alt="Ilustración Fase 5: Retargeting y Automatización"
                                                class="w-full max-h-56 sm:max-h-64 object-contain transition-transform duration-300 group-hover/img:scale-105"
                                                loading="lazy"
                                            >
                                            <div class="absolute inset-0 bg-slate-900/0 group-hover/img:bg-slate-900/15 transition-all flex items-end justify-end p-2 pointer-events-none">
                                                <span class="opacity-0 group-hover/img:opacity-100 transition-opacity px-2.5 py-1 rounded-lg bg-slate-900/90 text-[10px] font-bold text-white shadow-md flex items-center gap-1 backdrop-blur-sm">
                                                    🔍 Ampliar diagrama
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-4 pt-4 border-t border-[var(--color-border)] space-y-2 text-xs text-[var(--color-text)]">
                                        <div class="flex items-center gap-2">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#8b5cf6] shrink-0"></span>
                                            <span><strong>Día 3:</strong> Envío de catálogo digital de armazones y micas protectoras.</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#8b5cf6] shrink-0"></span>
                                            <span><strong>Día 15:</strong> Cupón exclusivo del 20% de descuento corporativo.</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#8b5cf6] shrink-0"></span>
                                            <span><strong>Día 90:</strong> Recordatorio preventivo de chequeo visual anual.</span>
                                        </div>
                                    </div>
                                    <div class="mt-5 pt-3 flex items-center justify-between">
                                        <a
                                            href="/admin/retargeting-campaigns-page"
                                            class="inline-flex items-center gap-2 text-xs font-bold text-[#8b5cf6] hover:text-[#7c3aed] transition group/btn"
                                        >
                                            <span>Panel de Campañas Retargeting</span>
                                            <span class="group-hover/btn:translate-x-1 transition-transform">→</span>
                                        </a>
                                        <span class="text-xl">🔄</span>
                                    </div>
                                </div>
                            </div>
                            <!-- Nodo Central -->
                            <div class="absolute left-6 md:left-1/2 -translate-x-1/2 top-6 md:top-1/2 md:-translate-y-1/2 z-10 flex items-center justify-center">
                                <div class="w-12 h-12 rounded-2xl bg-white border-2 border-[#8b5cf6] shadow-lg shadow-[#8b5cf6]/30 flex items-center justify-center text-lg text-[#8b5cf6] font-black group-hover:scale-110 transition-transform">
                                    05
                                </div>
                            </div>
                            <!-- Espaciador Desktop Derecha -->
                            <div class="hidden md:block md:w-1/2"></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <!-- Footer estilo Telemedicina (Deep Navy #1a365d) -->
    <footer class="bg-[var(--color-bg-dark)] text-white py-12 px-6 relative z-10 mt-16 border-t border-slate-700/50">
        <div class="max-w-6xl mx-auto flex flex-col md:flex-row items-center justify-between gap-6 text-xs text-white/70">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-[var(--color-primary)] to-[var(--color-secondary)] flex items-center justify-center text-white text-base shadow-sm">
                    👁️
                </div>
                <div>
                    <span class="font-bold text-white text-sm block">Visual Corporativo</span>
                    <span class="text-white/60">Cuidado Visual Laboral con Welch Allyn Spot Vision Screener</span>
                </div>
            </div>
            <div class="flex flex-wrap items-center justify-center gap-4 text-center">
                <span class="px-3 py-1 rounded-full bg-white/10 text-white/90 font-medium">HIPAA Compliant</span>
                <span class="px-3 py-1 rounded-full bg-white/10 text-white/90 font-medium">NOM-035 / NOM-036</span>
                <span class="px-3 py-1 rounded-full bg-white/10 text-white/90 font-medium">Seguridad de Datos Clínicos</span>
            </div>
            <div class="text-center md:text-right text-white/50">
                Directivas de <code class="text-white/80 bg-white/10 px-1.5 py-0.5 rounded">AuditoriaDeSeguridad.md</code> y <code class="text-white/80 bg-white/10 px-1.5 py-0.5 rounded">docs/AGENTS.md</code>.
            </div>
        </div>
    </footer>

    <!-- Modal Lightbox estilo Telemedicina para Ampliar Infografías -->
    <div id="phaseImageModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 backdrop-blur-md p-4 transition-all duration-300" role="dialog" aria-modal="true" aria-labelledby="phaseModalTitle">
        <div class="relative max-w-2xl w-full bg-white border border-[var(--color-border)] rounded-3xl p-5 sm:p-6 shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
            <div class="flex items-center justify-between pb-3.5 border-b border-[var(--color-border)]">
                <div class="flex items-center gap-2.5">
                    <span class="w-3 h-3 rounded-full bg-[var(--color-primary)] animate-pulse"></span>
                    <h4 id="phaseModalTitle" class="text-sm sm:text-base font-bold text-[var(--color-text)] tracking-wide">
                        Diagrama de Fase
                    </h4>
                </div>
                <button
                    type="button"
                    onclick="closePhaseModal()"
                    class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-900 flex items-center justify-center text-sm font-black transition cursor-pointer"
                    aria-label="Cerrar modal"
                >
                    ✕
                </button>
            </div>
            <div class="flex-1 overflow-auto py-3.5 flex items-center justify-center bg-slate-50/80 rounded-2xl my-3 p-3 sm:p-4 border border-[var(--color-border)]">
                <img
                    id="phaseModalImage"
                    src=""
                    alt="Diagrama ampliado de la fase"
                    class="max-h-[70vh] w-auto max-w-full object-contain rounded-xl shadow-md bg-white p-2"
                >
            </div>
            <div class="pt-2 flex items-center justify-between text-xs text-[var(--color-text-muted)]">
                <span>Esquema gráfico del flujo operativo de tamizaje</span>
                <button
                    type="button"
                    onclick="closePhaseModal()"
                    class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold transition text-xs cursor-pointer"
                >
                    Cerrar (Esc)
                </button>
            </div>
        </div>
    </div>

    <script>
        function openPhaseModal(imageSrc, title) {
            const modal = document.getElementById('phaseImageModal');
            const modalImg = document.getElementById('phaseModalImage');
            const modalTitle = document.getElementById('phaseModalTitle');

            if (!modal || !modalImg || !modalTitle) return;

            modalImg.src = imageSrc;
            modalTitle.textContent = title;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closePhaseModal() {
            const modal = document.getElementById('phaseImageModal');
            if (!modal) return;

            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
        }

        document.getElementById('phaseImageModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closePhaseModal();
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closePhaseModal();
            }
        });
    </script>
</body>
</html>
