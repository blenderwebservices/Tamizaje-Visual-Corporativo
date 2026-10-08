<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pase de Tamizaje Visual | SpotVision</title>
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
    <header class="py-4 px-6 border-b border-slate-800 text-center">
        <h1 class="text-sm font-bold text-slate-400 uppercase tracking-widest">Pase de Acceso a Tamizaje</h1>
    </header>

    <main class="flex-1 py-8 px-4 flex items-center justify-center">
        <div class="w-full max-w-md bg-slate-900 border border-slate-800 rounded-3xl p-6 sm:p-8 shadow-2xl text-center relative overflow-hidden">
            <div class="w-16 h-16 bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 rounded-2xl mx-auto flex items-center justify-center text-3xl mb-4 shadow-lg shadow-emerald-500/10">
                ✓
            </div>

            <h2 class="text-2xl font-black text-white">¡Registro Confirmado!</h2>
            <p class="text-xs text-slate-400 mt-1">Acércate al módulo del optometrista para tu examen.</p>

            <!-- Tarjeta de Identificación para el SpotVision -->
            <div class="mt-6 p-6 rounded-2xl bg-gradient-to-b from-slate-800 to-slate-800/80 border border-slate-700 shadow-inner">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Tu Clave de Examen (ID Sujeto)</span>
                <p class="text-4xl font-extrabold text-emerald-400 font-mono tracking-widest my-2">
                    {{ $client->subject_code ?: $client->client_code }}
                </p>

                <!-- Gráfico de Simulación QR para Escaneo Ágil -->
                <div class="w-36 h-36 bg-white p-2 rounded-xl mx-auto my-3 shadow-md flex items-center justify-center">
                    <svg viewBox="0 0 100 100" class="w-full h-full text-slate-900 fill-current">
                        <!-- Generador de patrón QR representativo -->
                        <path d="M0,0 h30 v30 h-30 z M10,10 h10 v10 h-10 z M70,0 h30 v30 h-30 z M80,10 h10 v10 h-10 z M0,70 h30 v30 h-30 z M10,80 h10 v10 h-10 z M40,10 h20 v10 h-20 z M10,40 h20 v20 h-20 z M40,40 h20 v20 h-20 z M70,40 h20 v20 h-20 z M40,70 h20 v20 h-20 z M70,70 h30 v10 h-30 z M80,85 h20 v15 h-20 z" />
                    </svg>
                </div>

                <div class="text-left mt-4 pt-4 border-t border-slate-700/60 text-xs text-slate-300 space-y-1">
                    <p><strong>Colaborador:</strong> {{ $client->full_name }}</p>
                    <p><strong>Empresa:</strong> {{ $client->company?->name ?: 'Jornada Corporativa' }}</p>
                    <p><strong>WhatsApp:</strong> {{ $client->phone }}</p>
                </div>
            </div>

            <div class="mt-6 p-4 rounded-xl bg-emerald-950/40 border border-emerald-800/30 text-emerald-300 text-xs text-left flex items-start gap-3">
                <span class="text-lg leading-none">⏱️</span>
                <p>El escaneo toma solo <strong>10 segundos</strong> a 1 metro de distancia con luces y sonidos guía del equipo SpotVision.</p>
            </div>

            @if($client->latestScreening)
                <a
                    href="{{ $client->latestScreening->public_report_url }}"
                    class="block w-full mt-4 py-3 px-4 bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs uppercase tracking-wider rounded-xl transition shadow-md"
                >
                    Ver Mi Reporte Visual Completo →
                </a>
            @endif
        </div>
    </main>

    <footer class="py-4 text-center text-xs text-slate-600 border-t border-slate-900">
        Visual Corporativo • Cuidado Visual en el Entorno Laboral
    </footer>
</body>
</html>
