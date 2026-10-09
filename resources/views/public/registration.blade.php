<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jornada de Salud Visual Corporativa | Registro Rápido</title>
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
            box-shadow: 8px 8px 30px rgba(148, 163, 184, 0.15), -8px -8px 30px rgba(255, 255, 255, 0.9);
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

        .navbar-tele {
            background: rgba(248, 250, 252, 0.88);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--color-border);
        }

        .form-input {
            border: 1.5px solid var(--color-border);
            background: #ffffff;
            border-radius: 14px;
            padding: 12px 16px;
            font-size: 0.875rem;
            color: var(--color-text);
            transition: all 0.2s ease;
            width: 100%;
        }

        .form-input:focus {
            border-color: var(--color-primary);
            outline: none;
            box-shadow: 0 0 0 4px rgba(79, 140, 255, 0.12);
        }
    </style>
</head>
<body class="telemedicine min-h-screen flex flex-col justify-between antialiased selection:bg-[#4f8cff] selection:text-white relative">

    <!-- Halos de luz ambiental estilo Telemedicina -->
    <div class="fixed inset-0 pointer-events-none z-0 overflow-hidden">
        <div class="absolute top-10 right-10 w-[450px] h-[450px] rounded-full bg-[var(--color-primary)]/10 blur-3xl"></div>
        <div class="absolute bottom-10 left-10 w-[400px] h-[400px] rounded-full bg-[var(--color-secondary)]/10 blur-3xl"></div>
    </div>

    <!-- Barra Superior -->
    <header class="navbar-tele sticky top-0 z-50 py-3.5 px-6">
        <div class="max-w-xl mx-auto flex items-center justify-between">
            <a href="/" class="flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-[var(--color-primary)] to-[var(--color-secondary)] flex items-center justify-center text-white font-black text-xl shadow-md shadow-[#4f8cff]/20 group-hover:scale-105 transition-transform">
                    👁️
                </div>
                <div>
                    <h1 class="text-base font-extrabold text-[var(--color-text)] tracking-tight leading-none">Visual Corporativo</h1>
                    <p class="text-xs text-[var(--color-primary)] font-semibold mt-0.5">Jornada de Tamizaje en Sitio</p>
                </div>
            </a>
            <span class="badge badge-primary text-xs !py-1 !px-3">
                <span class="w-2 h-2 rounded-full bg-[var(--color-primary)] animate-pulse"></span> SpotVision 10s
            </span>
        </div>
    </header>

    <!-- Contenido Principal -->
    <main class="flex-1 py-8 sm:py-12 px-4 flex items-center justify-center relative z-10">
        <div class="w-full max-w-xl soft-card p-6 sm:p-9 relative overflow-hidden bg-white">
            <!-- Destello de fondo decorativo -->
            <div class="absolute -top-24 -right-24 w-60 h-60 bg-[var(--color-primary)]/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="mb-6 text-center sm:text-left">
                <div class="badge badge-primary mb-3">
                    <span>🚀 Fase 1: Registro Ágil</span>
                </div>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-[var(--color-text)] tracking-tight">¡Hola! Escaneo rápido en 10s</h2>
                <p class="text-[var(--color-text-muted)] text-sm mt-1.5 leading-relaxed">
                    Regístrate para recibir tus resultados optométricos y análisis clínico al instante vía WhatsApp.
                </p>
            </div>

            @if ($errors->any())
                <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-sm">
                    <p class="font-bold mb-1 flex items-center gap-1.5">
                        <span>⚠️</span> Por favor verifica los siguientes campos:
                    </p>
                    <ul class="list-disc list-inside text-xs space-y-0.5 text-rose-600">
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
                    <label class="block text-xs font-bold text-[var(--color-text)] uppercase tracking-wider mb-1.5">
                        Empresa / Jornada
                    </label>
                    <select name="company_id" class="form-input cursor-pointer">
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
                    <label class="block text-xs font-bold text-[var(--color-text)] uppercase tracking-wider mb-1.5">
                        Nombre Completo <span class="text-[var(--color-primary)]">*</span>
                    </label>
                    <input
                        type="text"
                        name="full_name"
                        value="{{ old('full_name') }}"
                        required
                        placeholder="Ej: Izamar Rodriguez Cabello"
                        class="form-input placeholder-slate-400"
                    />
                </div>

                <!-- WhatsApp y Correo -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-[var(--color-text)] uppercase tracking-wider mb-1.5">
                            WhatsApp / Teléfono <span class="text-[var(--color-primary)]">*</span>
                        </label>
                        <input
                            type="tel"
                            name="phone"
                            value="{{ old('phone') }}"
                            required
                            placeholder="Ej: 81 1234 5678"
                            class="form-input placeholder-slate-400"
                        />
                        <p class="text-[11px] text-[var(--color-text-muted)] mt-1">Aquí recibirás tu reporte digital.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[var(--color-text)] uppercase tracking-wider mb-1.5">
                            Correo Electrónico
                        </label>
                        <input
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="nombre@empresa.com"
                            class="form-input placeholder-slate-400"
                        />
                    </div>
                </div>

                <!-- Fecha de Nacimiento y Sexo (Deduplicación) -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-[var(--color-text)] uppercase tracking-wider mb-1.5">
                            Fecha de Nacimiento
                        </label>
                        <input
                            type="date"
                            name="birth_date"
                            value="{{ old('birth_date') }}"
                            class="form-input"
                        />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[var(--color-text)] uppercase tracking-wider mb-1.5">
                            Sexo
                        </label>
                        <select name="gender" class="form-input cursor-pointer">
                            <option value="">Elegir...</option>
                            <option value="H" {{ old('gender') === 'H' ? 'selected' : '' }}>Hombre</option>
                            <option value="M" {{ old('gender') === 'M' ? 'selected' : '' }}>Mujer</option>
                            <option value="O" {{ old('gender') === 'O' ? 'selected' : '' }}>Otro</option>
                        </select>
                    </div>
                </div>

                <!-- Consentimiento Legal Garantizado -->
                <div class="pt-2">
                    <label class="flex items-start gap-3 p-3.5 rounded-2xl bg-[var(--color-bg-alt)] border border-[var(--color-border)] cursor-pointer hover:border-[var(--color-primary)] transition">
                        <input
                            type="checkbox"
                            name="terms_accepted"
                            value="1"
                            required
                            class="mt-1 w-4 h-4 rounded text-[#4f8cff] focus:ring-[#4f8cff] border-slate-300"
                            {{ old('terms_accepted', '1') ? 'checked' : '' }}
                        />
                        <span class="text-xs text-[var(--color-text)] leading-relaxed">
                            <strong>Acepto términos y uso de datos.</strong> Autorizo el tratamiento de mis datos de salud visual exclusivamente para la entrega de resultados y recomendaciones clínicas de esta jornada laboral.
                        </span>
                    </label>
                </div>

                <!-- Botón de Envío -->
                <button
                    type="submit"
                    class="btn-primary w-full py-4 text-base uppercase tracking-wider font-bold shadow-lg shadow-[#4f8cff]/25 mt-4"
                >
                    <span>Generar Pase de Tamizaje</span>
                    <span>→</span>
                </button>
            </form>
        </div>
    </main>

    <!-- Pie de Página -->
    <footer class="py-4 text-center text-xs text-[var(--color-text-muted)] border-t border-[var(--color-border)] relative z-10 bg-white/70">
        Tecnología Welch Allyn Spot™ Vision Screener • Protocolo de Salud Visual Corporativa
    </footer>
</body>
</html>
