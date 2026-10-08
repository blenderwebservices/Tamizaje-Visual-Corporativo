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

            <!-- Las 5 Fases del Bosquejo -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <!-- Fase 1 -->
                <div class="bg-slate-900/80 border border-slate-800 p-5 rounded-2xl relative overflow-hidden flex flex-col justify-between hover:border-slate-700 transition">
                    <div>
                        <span class="text-[11px] font-black text-emerald-400 uppercase tracking-wider block">Fase 1</span>
                        <h3 class="text-base font-bold text-white mt-1">Atracción y Captura</h3>
                        <p class="text-xs text-slate-400 mt-2">
                            Formulario móvil en 10s con consentimiento legal garantizado y generación de ID Sujeto.
                        </p>
                    </div>
                    <span class="text-2xl mt-4 block">📱</span>
                </div>

                <!-- Fase 2 -->
                <div class="bg-slate-900/80 border border-slate-800 p-5 rounded-2xl relative overflow-hidden flex flex-col justify-between hover:border-slate-700 transition">
                    <div>
                        <span class="text-[11px] font-black text-teal-400 uppercase tracking-wider block">Fase 2</span>
                        <h3 class="text-base font-bold text-white mt-1">Tamizaje SpotVision</h3>
                        <p class="text-xs text-slate-400 mt-2">
                            Importación USB de PDFs, extracción de imagen de reporte, deduplicación de pacientes y OD/OS.
                        </p>
                    </div>
                    <span class="text-2xl mt-4 block">🔬</span>
                </div>

                <!-- Fase 3 -->
                <div class="bg-slate-900/80 border border-slate-800 p-5 rounded-2xl relative overflow-hidden flex flex-col justify-between hover:border-slate-700 transition">
                    <div>
                        <span class="text-[11px] font-black text-cyan-400 uppercase tracking-wider block">Fase 3</span>
                        <h3 class="text-base font-bold text-white mt-1">Entrega Digital</h3>
                        <p class="text-xs text-slate-400 mt-2">
                            Análisis rápido clínico y reporte con enlace personalizado disparado a WhatsApp en un clic.
                        </p>
                    </div>
                    <span class="text-2xl mt-4 block">💬</span>
                </div>

                <!-- Fase 4 -->
                <div class="bg-slate-900/80 border border-slate-800 p-5 rounded-2xl relative overflow-hidden flex flex-col justify-between hover:border-slate-700 transition">
                    <div>
                        <span class="text-[11px] font-black text-amber-400 uppercase tracking-wider block">Fase 4</span>
                        <h3 class="text-base font-bold text-white mt-1">Cierre en Sitio CRM</h3>
                        <p class="text-xs text-slate-400 mt-2">
                            Compra en lugar (Cliente Activo) o clasificación de prospecto para refracción y lentes de seguridad.
                        </p>
                    </div>
                    <span class="text-2xl mt-4 block">🛒</span>
                </div>

                <!-- Fase 5 -->
                <div class="bg-slate-900/80 border border-slate-800 p-5 rounded-2xl relative overflow-hidden flex flex-col justify-between hover:border-slate-700 transition">
                    <div>
                        <span class="text-[11px] font-black text-indigo-400 uppercase tracking-wider block">Fase 5</span>
                        <h3 class="text-base font-bold text-white mt-1">Retargeting</h3>
                        <p class="text-xs text-slate-400 mt-2">
                            Seguimiento programado: Día 3 (Catálogo), Día 15 (Cupón 20%), Día 90 (Examen clínico).
                        </p>
                    </div>
                    <span class="text-2xl mt-4 block">🔄</span>
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
