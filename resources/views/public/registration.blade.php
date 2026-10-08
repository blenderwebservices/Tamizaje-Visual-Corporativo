<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jornada de Salud Visual Corporativa | Registro Rápido</title>
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
                    },
                    colors: {
                        brand: {
                            50: '#ecfdf5',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                            900: '#064e3b',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen flex flex-col justify-between antialiased selection:bg-emerald-500 selection:text-white">
    <!-- Barra Superior -->
    <header class="border-b border-slate-800 bg-slate-900/80 backdrop-blur sticky top-0 z-50 py-4 px-6">
        <div class="max-w-xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-emerald-500 to-teal-400 flex items-center justify-center text-slate-950 font-black text-xl shadow-lg shadow-emerald-500/20">
                    👁️
                </div>
                <div>
                    <h1 class="text-base font-bold text-white tracking-tight leading-none">Visual Corporativo</h1>
                    <p class="text-xs text-emerald-400 font-medium">Jornada de Tamizaje en Sitio</p>
                </div>
            </div>
            <span class="text-xs px-2.5 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-300 font-semibold flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span> SpotVision 10s
            </span>
        </div>
    </header>

    <!-- Contenido Principal -->
    <main class="flex-1 py-8 px-4 flex items-center justify-center">
        <div class="w-full max-w-xl bg-slate-800/90 border border-slate-700/80 rounded-3xl p-6 sm:p-8 shadow-2xl relative overflow-hidden">
            <!-- Destello de fondo decorativo -->
            <div class="absolute -top-24 -right-24 w-60 h-60 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="mb-6 text-center sm:text-left">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-700/60 border border-slate-600 text-slate-300 text-xs font-semibold mb-3">
                    🚀 Fase 1: Registro Ágil
                </div>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">¡Hola! Escaneo rápido en 10s</h2>
                <p class="text-slate-400 text-sm mt-1.5">
                    Regístrate para recibir tus resultados optométricos y análisis clínico al instante vía WhatsApp.
                </p>
            </div>

            @if ($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-300 text-sm">
                    <p class="font-bold mb-1">Por favor verifica los siguientes campos:</p>
                    <ul class="list-disc list-inside text-xs space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('registration.submit') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Empresa -->
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">
                        Empresa / Jornada
                    </label>
                    <select name="company_id" class="w-full bg-slate-900/90 border border-slate-700 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
                        <option value="">Selecciona tu empresa o planta...</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}" {{ (old('company_id', $selectedCompanyId) == $company->id) ? 'selected' : '' }}>
                                {{ $company->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Nombre Completo -->
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">
                        Nombre Completo <span class="text-emerald-400">*</span>
                    </label>
                    <input
                        type="text"
                        name="full_name"
                        value="{{ old('full_name') }}"
                        required
                        placeholder="Ej: Izamar Rodriguez Cabello"
                        class="w-full bg-slate-900/90 border border-slate-700 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition"
                    />
                </div>

                <!-- WhatsApp y Correo -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">
                            WhatsApp / Teléfono <span class="text-emerald-400">*</span>
                        </label>
                        <input
                            type="tel"
                            name="phone"
                            value="{{ old('phone') }}"
                            required
                            placeholder="Ej: 81 1234 5678"
                            class="w-full bg-slate-900/90 border border-slate-700 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition"
                        />
                        <p class="text-[11px] text-slate-400 mt-1">Aquí recibirás tu reporte digital.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">
                            Correo Electrónico
                        </label>
                        <input
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="nombre@empresa.com"
                            class="w-full bg-slate-900/90 border border-slate-700 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition"
                        />
                    </div>
                </div>

                <!-- Fecha de Nacimiento y Sexo (Deduplicación) -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">
                            Fecha de Nacimiento
                        </label>
                        <input
                            type="date"
                            name="birth_date"
                            value="{{ old('birth_date') }}"
                            class="w-full bg-slate-900/90 border border-slate-700 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 transition"
                        />
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">
                            Sexo
                        </label>
                        <select name="gender" class="w-full bg-slate-900/90 border border-slate-700 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
                            <option value="">Elegir...</option>
                            <option value="H" {{ old('gender') === 'H' ? 'selected' : '' }}>Hombre</option>
                            <option value="M" {{ old('gender') === 'M' ? 'selected' : '' }}>Mujer</option>
                            <option value="O" {{ old('gender') === 'O' ? 'selected' : '' }}>Otro</option>
                        </select>
                    </div>
                </div>

                <!-- Consentimiento Legal Garantizado -->
                <div class="pt-2">
                    <label class="flex items-start gap-3 p-3.5 rounded-2xl bg-slate-900/60 border border-slate-700/80 cursor-pointer hover:border-emerald-500/50 transition">
                        <input
                            type="checkbox"
                            name="terms_accepted"
                            value="1"
                            required
                            class="mt-1 w-4 h-4 rounded text-emerald-500 focus:ring-emerald-500 focus:ring-offset-slate-900 bg-slate-800 border-slate-600"
                            {{ old('terms_accepted', '1') ? 'checked' : '' }}
                        />
                        <span class="text-xs text-slate-300 leading-relaxed">
                            <strong>Acepto términos y uso de datos.</strong> Autorizo el tratamiento de mis datos de salud visual exclusivamente para la entrega de resultados y recomendaciones clínicas de esta jornada laboral.
                        </span>
                    </label>
                </div>

                <!-- Botón de Envío -->
                <button
                    type="submit"
                    class="w-full py-4 px-6 rounded-2xl bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-400 hover:to-teal-400 text-slate-950 font-black text-base tracking-wide uppercase transition shadow-lg shadow-emerald-500/25 active:scale-[0.99] flex items-center justify-center gap-2 mt-4"
                >
                    <span>Generar Pase de Tamizaje</span>
                    <span>→</span>
                </button>
            </form>
        </div>
    </main>

    <!-- Pie de Página -->
    <footer class="py-4 text-center text-xs text-slate-500 border-t border-slate-800/80">
        Tecnología Welch Allyn Spot™ Vision Screener • Protocolo de Salud Visual Corporativa
    </footer>
</body>
</html>
