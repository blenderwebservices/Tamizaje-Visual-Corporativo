<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tamizaje Visual Corporativo | SpotVision Hub</title>
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
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex flex-col justify-between antialiased selection:bg-emerald-500 selection:text-white">

    <!-- Header de Navegación -->
    <header class="border-b border-slate-800 bg-slate-900/80 backdrop-blur sticky top-0 z-50 py-4 px-6">
        <div class="max-w-6xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-emerald-500 to-teal-400 flex items-center justify-center text-slate-950 text-xl font-black shadow-lg shadow-emerald-500/20">
                    👁️
                </div>
                <div>
                    <h1 class="text-base font-extrabold text-white tracking-tight leading-none">Visual Corporativo</h1>
                    <p class="text-xs text-emerald-400 font-medium">De la Atracción al Retargeting • SpotVision</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a
                    href="{{ route('registration.form') }}"
                    class="hidden sm:inline-flex px-4 py-2 text-xs font-bold text-slate-300 hover:text-white rounded-xl bg-slate-800 hover:bg-slate-700 transition border border-slate-700"
                >
                    Registro Móvil (Fase 1)
                </a>
                <a
                    href="/admin"
                    class="px-5 py-2.5 text-xs font-bold uppercase tracking-wider text-slate-950 bg-emerald-400 hover:bg-emerald-300 rounded-xl transition shadow-lg shadow-emerald-400/20 active:scale-95"
                >
                    Panel Filament →
                </a>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <main class="flex-1 py-12 px-6">
        <div class="max-w-6xl mx-auto space-y-16">

            <!-- Encabezado Principal -->
            <div class="text-center max-w-3xl mx-auto space-y-4">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-300 text-xs font-bold tracking-wide">
                    ✨ Solución Integral Automatizada
                </div>
                <h2 class="text-3xl sm:text-5xl font-black text-white tracking-tight leading-tight">
                    Flujo de Tamizaje Visual Corporativo
                </h2>
                <p class="text-base sm:text-lg text-slate-400 leading-relaxed">
                    Plataforma conectada con autorrefractómetros <strong>Welch Allyn Spot Vision Screener</strong> para importar archivos PDF desde USB, deduplicar pacientes, extraer datos clínicos mediante IA y programar envíos y retargeting vía WhatsApp.
                </p>

                <div class="pt-4 flex flex-wrap items-center justify-center gap-4">
                    <a
                        href="/admin"
                        class="px-6 py-3.5 rounded-2xl bg-gradient-to-r from-emerald-500 to-teal-500 text-slate-950 font-black text-sm uppercase tracking-wide transition shadow-xl shadow-emerald-500/25 hover:brightness-110 active:scale-95 flex items-center gap-2"
                    >
                        <span>Entrar al Panel de Control (Filament)</span>
                        <span>🚀</span>
                    </a>
                    <a
                        href="{{ route('registration.form') }}"
                        class="px-6 py-3.5 rounded-2xl bg-slate-800 hover:bg-slate-700 text-white font-bold text-sm border border-slate-700 transition active:scale-95 flex items-center gap-2"
                    >
                        <span>Simular Registro de Paciente en Celular</span>
                        <span>📱</span>
                    </a>
                </div>

                <!-- Credenciales Rápidas para Demostración -->
                <div class="p-4 bg-slate-900/90 border border-slate-800 rounded-2xl text-xs text-slate-400 max-w-md mx-auto mt-4">
                    <p class="font-bold text-slate-300 mb-1">Acceso al Panel Administrativo:</p>
                    <p>Usuario: <span class="text-emerald-400 font-mono">admin@visualcorporativo.com</span></p>
                    <p>Contraseña: <span class="text-emerald-400 font-mono">admin123</span></p>
                </div>
            </div>

            <!-- Sección de Metodología y Timeline Vertical de 5 Fases -->
            <div class="space-y-12">
                <!-- Encabezado de la sección -->
                <div class="text-center max-w-2xl mx-auto space-y-3">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-800/90 border border-slate-700 text-emerald-400 text-xs font-semibold tracking-wide">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        Flujo Operativo End-to-End
                    </div>
                    <h3 class="text-2xl sm:text-4xl font-extrabold text-white tracking-tight">
                        Metodología en 5 Fases
                    </h3>
                    <p class="text-sm sm:text-base text-slate-400">
                        Un recorrido integral y automatizado desde el primer registro del colaborador en el corporativo hasta la fidelización y retargeting inteligente.
                    </p>
                </div>

                <!-- Timeline Vertical Responsivo -->
                <div class="relative max-w-5xl mx-auto pt-4 pb-8">
                    <!-- Línea Central del Timeline con Gradiente Luminoso -->
                    <div class="absolute left-6 md:left-1/2 top-6 bottom-6 w-1 -translate-x-1/2 bg-gradient-to-b from-emerald-500 via-teal-400 via-cyan-400 via-amber-400 to-indigo-500 rounded-full shadow-[0_0_20px_rgba(16,185,129,0.25)]"></div>

                    <div class="space-y-12 md:space-y-16">
                        <!-- FASE 1: Atracción y Captura (Izquierda en Desktop) -->
                        <div class="relative flex flex-col md:flex-row items-start md:items-center group">
                            <!-- Card (Desktop Izquierda) -->
                            <div class="w-full md:w-1/2 pl-16 md:pl-0 md:pr-12">
                                <div class="bg-slate-900/90 border border-slate-800 rounded-3xl p-6 sm:p-7 relative overflow-hidden transition-all duration-300 hover:border-emerald-500/50 hover:shadow-2xl hover:shadow-emerald-500/10 hover:-translate-y-1">
                                    <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-500/10 rounded-full blur-2xl pointer-events-none group-hover:bg-emerald-500/20 transition-all"></div>
                                    <div class="flex items-center justify-between gap-3 mb-3">
                                        <span class="px-3 py-1 rounded-full text-[11px] font-black uppercase tracking-wider bg-emerald-500/15 border border-emerald-500/30 text-emerald-400">
                                            Fase 01 • En Sitio & Móvil
                                        </span>
                                        <span class="text-xs font-semibold text-slate-400 flex items-center gap-1">
                                            ⏱️ ~10 seg
                                        </span>
                                    </div>
                                    <h4 class="text-lg sm:text-xl font-bold text-white group-hover:text-emerald-300 transition-colors">
                                        Atracción y Captura Ágil
                                    </h4>
                                    <p class="text-xs sm:text-sm text-slate-400 mt-2 leading-relaxed">
                                        Registro ultrarrápido desde el smartphone del colaborador mediante escaneo de código QR en el stand corporativo, eliminando filas y capturas manuales de datos.
                                    </p>
                                    <div class="mt-4 pt-4 border-t border-slate-800 space-y-2 text-xs text-slate-300">
                                        <div class="flex items-center gap-2">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 shrink-0"></span>
                                            <span>Formulario móvil ligero con validación de teléfono WhatsApp.</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 shrink-0"></span>
                                            <span>Consentimiento informado y aviso de privacidad legalmente garantizados.</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 shrink-0"></span>
                                            <span>Emisión de Pase Digital con UUID único y código de barras.</span>
                                        </div>
                                    </div>
                                    <div class="mt-5 pt-3 flex items-center justify-between">
                                        <a
                                            href="{{ route('registration.form') }}"
                                            class="inline-flex items-center gap-2 text-xs font-bold text-emerald-400 hover:text-emerald-300 transition group/btn"
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
                                <div class="w-12 h-12 rounded-2xl bg-slate-950 border-2 border-emerald-500 shadow-lg shadow-emerald-500/40 flex items-center justify-center text-lg text-emerald-400 font-black group-hover:scale-110 transition-transform">
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
                                <div class="w-12 h-12 rounded-2xl bg-slate-950 border-2 border-teal-400 shadow-lg shadow-teal-500/40 flex items-center justify-center text-lg text-teal-400 font-black group-hover:scale-110 transition-transform">
                                    02
                                </div>
                            </div>
                            <!-- Card (Desktop Derecha) -->
                            <div class="w-full md:w-1/2 pl-16 md:pl-12 md:pr-0">
                                <div class="bg-slate-900/90 border border-slate-800 rounded-3xl p-6 sm:p-7 relative overflow-hidden transition-all duration-300 hover:border-teal-400/50 hover:shadow-2xl hover:shadow-teal-500/10 hover:-translate-y-1">
                                    <div class="absolute top-0 right-0 w-32 h-32 bg-teal-500/10 rounded-full blur-2xl pointer-events-none group-hover:bg-teal-500/20 transition-all"></div>
                                    <div class="flex items-center justify-between gap-3 mb-3">
                                        <span class="px-3 py-1 rounded-full text-[11px] font-black uppercase tracking-wider bg-teal-500/15 border border-teal-500/30 text-teal-400">
                                            Fase 02 • Equipamiento Médico
                                        </span>
                                        <span class="text-xs font-semibold text-slate-400 flex items-center gap-1">
                                            ⏱️ 5 seg / ojo
                                        </span>
                                    </div>
                                    <h4 class="text-lg sm:text-xl font-bold text-white group-hover:text-teal-300 transition-colors">
                                        Tamizaje y Extracción SpotVision
                                    </h4>
                                    <p class="text-xs sm:text-sm text-slate-400 mt-2 leading-relaxed">
                                        Evaluación refractiva binocular con autorrefractómetro Welch Allyn Spot Vision Screener. Carga por USB de PDFs y análisis óptico automatizado.
                                    </p>
                                    <div class="mt-4 pt-4 border-t border-slate-800 space-y-2 text-xs text-slate-300">
                                        <div class="flex items-center gap-2">
                                            <span class="w-1.5 h-1.5 rounded-full bg-teal-400 shrink-0"></span>
                                            <span>Medición precisa de Esfera, Cilindro, Eje y Distancia Pupilar (OD/OS).</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="w-1.5 h-1.5 rounded-full bg-teal-400 shrink-0"></span>
                                            <span>Extracción y recorte automático de la gráfica/imagen del reporte impreso.</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="w-1.5 h-1.5 rounded-full bg-teal-400 shrink-0"></span>
                                            <span>Deduplicación inteligente por Nombre, Folio e ID Sujeto.</span>
                                        </div>
                                    </div>
                                    <div class="mt-5 pt-3 flex items-center justify-between">
                                        <a
                                            href="/admin/screenings"
                                            class="inline-flex items-center gap-2 text-xs font-bold text-teal-400 hover:text-teal-300 transition group/btn"
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
                                <div class="bg-slate-900/90 border border-slate-800 rounded-3xl p-6 sm:p-7 relative overflow-hidden transition-all duration-300 hover:border-cyan-400/50 hover:shadow-2xl hover:shadow-cyan-500/10 hover:-translate-y-1">
                                    <div class="absolute top-0 right-0 w-32 h-32 bg-cyan-500/10 rounded-full blur-2xl pointer-events-none group-hover:bg-cyan-500/20 transition-all"></div>
                                    <div class="flex items-center justify-between gap-3 mb-3">
                                        <span class="px-3 py-1 rounded-full text-[11px] font-black uppercase tracking-wider bg-cyan-500/15 border border-cyan-500/30 text-cyan-400">
                                            Fase 03 • Comunicación Inmediata
                                        </span>
                                        <span class="text-xs font-semibold text-slate-400 flex items-center gap-1">
                                            ⚡ Instantáneo
                                        </span>
                                    </div>
                                    <h4 class="text-lg sm:text-xl font-bold text-white group-hover:text-cyan-300 transition-colors">
                                        Entrega Digital y Reporte WhatsApp
                                    </h4>
                                    <p class="text-xs sm:text-sm text-slate-400 mt-2 leading-relaxed">
                                        Análisis clínico inmediato con semáforo de riesgo visual y despacho automático del reporte interactivo con enlace personalizado a WhatsApp en un clic.
                                    </p>
                                    <div class="mt-4 pt-4 border-t border-slate-800 space-y-2 text-xs text-slate-300">
                                        <div class="flex items-center gap-2">
                                            <span class="w-1.5 h-1.5 rounded-full bg-cyan-400 shrink-0"></span>
                                            <span>Semáforo clínico tripartito: Normal, Sospechoso o Crítico.</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="w-1.5 h-1.5 rounded-full bg-cyan-400 shrink-0"></span>
                                            <span>Notificación WhatsApp con enlace seguro para el paciente.</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="w-1.5 h-1.5 rounded-full bg-cyan-400 shrink-0"></span>
                                            <span>Visualizador web responsivo con opción de descarga de PDF.</span>
                                        </div>
                                    </div>
                                    <div class="mt-5 pt-3 flex items-center justify-between">
                                        <a
                                            href="/admin/whats-app-queue-page"
                                            class="inline-flex items-center gap-2 text-xs font-bold text-cyan-400 hover:text-cyan-300 transition group/btn"
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
                                <div class="w-12 h-12 rounded-2xl bg-slate-950 border-2 border-cyan-400 shadow-lg shadow-cyan-500/40 flex items-center justify-center text-lg text-cyan-400 font-black group-hover:scale-110 transition-transform">
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
                                <div class="w-12 h-12 rounded-2xl bg-slate-950 border-2 border-amber-400 shadow-lg shadow-amber-500/40 flex items-center justify-center text-lg text-amber-400 font-black group-hover:scale-110 transition-transform">
                                    04
                                </div>
                            </div>
                            <!-- Card (Desktop Derecha) -->
                            <div class="w-full md:w-1/2 pl-16 md:pl-12 md:pr-0">
                                <div class="bg-slate-900/90 border border-slate-800 rounded-3xl p-6 sm:p-7 relative overflow-hidden transition-all duration-300 hover:border-amber-400/50 hover:shadow-2xl hover:shadow-amber-500/10 hover:-translate-y-1">
                                    <div class="absolute top-0 right-0 w-32 h-32 bg-amber-500/10 rounded-full blur-2xl pointer-events-none group-hover:bg-amber-500/20 transition-all"></div>
                                    <div class="flex items-center justify-between gap-3 mb-3">
                                        <span class="px-3 py-1 rounded-full text-[11px] font-black uppercase tracking-wider bg-amber-500/15 border border-amber-500/30 text-amber-400">
                                            Fase 04 • Conversión Comercial
                                        </span>
                                        <span class="text-xs font-semibold text-slate-400 flex items-center gap-1">
                                            🎯 Stand en Sitio
                                        </span>
                                    </div>
                                    <h4 class="text-lg sm:text-xl font-bold text-white group-hover:text-amber-300 transition-colors">
                                        Cierre en Sitio y Gestión CRM
                                    </h4>
                                    <p class="text-xs sm:text-sm text-slate-400 mt-2 leading-relaxed">
                                        Canalización inmediata de colaboradores con alteraciones visuales. Selección de armazones oftálmicos, micas con filtro azul o lentes de seguridad graduados.
                                    </p>
                                    <div class="mt-4 pt-4 border-t border-slate-800 space-y-2 text-xs text-slate-300">
                                        <div class="flex items-center gap-2">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-400 shrink-0"></span>
                                            <span>Generación de orden comercial directa en sitio (Cliente Activo).</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-400 shrink-0"></span>
                                            <span>Catálogo óptico de seguridad industrial con descuento vía nómina.</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-400 shrink-0"></span>
                                            <span>Refracción fina complementaria en el consultorio móvil.</span>
                                        </div>
                                    </div>
                                    <div class="mt-5 pt-3 flex items-center justify-between">
                                        <a
                                            href="/admin/orders"
                                            class="inline-flex items-center gap-2 text-xs font-bold text-amber-400 hover:text-amber-300 transition group/btn"
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
                                <div class="bg-slate-900/90 border border-slate-800 rounded-3xl p-6 sm:p-7 relative overflow-hidden transition-all duration-300 hover:border-indigo-400/50 hover:shadow-2xl hover:shadow-indigo-500/10 hover:-translate-y-1">
                                    <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-500/10 rounded-full blur-2xl pointer-events-none group-hover:bg-indigo-500/20 transition-all"></div>
                                    <div class="flex items-center justify-between gap-3 mb-3">
                                        <span class="px-3 py-1 rounded-full text-[11px] font-black uppercase tracking-wider bg-indigo-500/15 border border-indigo-500/30 text-indigo-400">
                                            Fase 05 • Fidelización Automatizada
                                        </span>
                                        <span class="text-xs font-semibold text-slate-400 flex items-center gap-1">
                                            🔄 3 a 90 días
                                        </span>
                                    </div>
                                    <h4 class="text-lg sm:text-xl font-bold text-white group-hover:text-indigo-300 transition-colors">
                                        Retargeting y Drip Automático
                                    </h4>
                                    <p class="text-xs sm:text-sm text-slate-400 mt-2 leading-relaxed">
                                        Secuencia inteligente programada por WhatsApp para prospectos no compradores, reactivando el interés mediante promociones y recordatorios clínicos.
                                    </p>
                                    <div class="mt-4 pt-4 border-t border-slate-800 space-y-2 text-xs text-slate-300">
                                        <div class="flex items-center gap-2">
                                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 shrink-0"></span>
                                            <span><strong>Día 3:</strong> Envío de catálogo digital de armazones y micas protectoras.</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 shrink-0"></span>
                                            <span><strong>Día 15:</strong> Cupón exclusivo del 20% de descuento corporativo.</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 shrink-0"></span>
                                            <span><strong>Día 90:</strong> Recordatorio preventivo de chequeo visual anual.</span>
                                        </div>
                                    </div>
                                    <div class="mt-5 pt-3 flex items-center justify-between">
                                        <a
                                            href="/admin/retargeting-campaigns-page"
                                            class="inline-flex items-center gap-2 text-xs font-bold text-indigo-400 hover:text-indigo-300 transition group/btn"
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
                                <div class="w-12 h-12 rounded-2xl bg-slate-950 border-2 border-indigo-400 shadow-lg shadow-indigo-500/40 flex items-center justify-center text-lg text-indigo-400 font-black group-hover:scale-110 transition-transform">
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

    <!-- Footer -->
    <footer class="border-t border-slate-900 py-6 px-6 text-center text-xs text-slate-600">
        Plataforma adaptada a las directivas de seguridad de <code class="text-slate-400">AuditoriaDeSeguridad.md</code> y <code class="text-slate-400">docs/AGENTS.md</code>.
    </footer>
</body>
</html>
