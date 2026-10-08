@php
    $data = $this->getViewData();
@endphp

<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
            <!-- Izquierda: Estado Principal de la IA -->
            <div class="flex items-start sm:items-center gap-4">
                <div class="relative flex-shrink-0 mt-1 sm:mt-0">
                    @if ($data['isConfigured'])
                        <div class="flex items-center justify-center w-14 h-14 rounded-2xl bg-emerald-500/10 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 border border-emerald-500/30">
                            <x-heroicon-o-sparkles class="w-8 h-8 animate-pulse" />
                        </div>
                        <span class="absolute -top-1 -right-1 flex h-4 w-4">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-4 w-4 bg-emerald-500 border-2 border-white dark:border-gray-900"></span>
                        </span>
                    @else
                        <div class="flex items-center justify-center w-14 h-14 rounded-2xl bg-amber-500/10 dark:bg-amber-500/20 text-amber-600 dark:text-amber-400 border border-amber-500/30">
                            <x-heroicon-o-exclamation-triangle class="w-8 h-8" />
                        </div>
                        <span class="absolute -top-1 -right-1 flex h-4 w-4">
                            <span class="relative inline-flex rounded-full h-4 w-4 bg-amber-500 border-2 border-white dark:border-gray-900"></span>
                        </span>
                    @endif
                </div>

                <div class="space-y-1">
                    <div class="flex flex-wrap items-center gap-2">
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">
                            Motor de Inteligencia Artificial (SpotVision OCR)
                        </h2>

                        @if ($data['isConfigured'])
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-800">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                IA Activa & Operativa
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 dark:bg-amber-950/80 dark:text-amber-300 border border-amber-300 dark:border-amber-800">
                                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                Inactiva (Modo Respaldo)
                            </span>
                        @endif

                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-mono font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-950/70 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800">
                            {{ $data['model'] }}
                        </span>
                    </div>

                    <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">
                        @if ($data['isConfigured'])
                            Extracción multimodal activa con Google Gemini para interpretar imágenes de autorrefractómetro de Spot Vision.
                            <span class="font-mono text-xs opacity-75">({{ $data['maskedKey'] }})</span>
                        @else
                            No se detectó <code class="px-1 py-0.5 rounded bg-gray-100 dark:bg-gray-800 font-mono text-xs">GEMINI_API_KEY</code>. Los tamizajes se procesarán mediante extracción programática básica.
                        @endif
                    </p>
                </div>
            </div>

            <!-- Derecha: Botón de Test y Métricas Rápidas -->
            <div class="flex flex-wrap sm:flex-nowrap items-center gap-3">
                <x-filament::button
                    wire:click="testConnection"
                    wire:loading.attr="disabled"
                    color="{{ $data['isConfigured'] ? 'primary' : 'gray' }}"
                    icon="heroicon-m-signal"
                    size="sm"
                    outlined
                >
                    <span wire:loading.remove wire:target="testConnection">
                        Verificar Conexión API
                    </span>
                    <span wire:loading wire:target="testConnection">
                        Probando conexión...
                    </span>
                </x-filament::button>
            </div>
        </div>

        <!-- Banner de Resultado de la Prueba de Conexión -->
        @if ($testStatus)
            <div class="mt-4 p-3 rounded-xl border text-sm flex items-center justify-between gap-3 {{ $testStatus === 'success' ? 'bg-emerald-50 dark:bg-emerald-950/50 text-emerald-800 dark:text-emerald-200 border-emerald-300 dark:border-emerald-800' : 'bg-rose-50 dark:bg-rose-950/50 text-rose-800 dark:text-rose-200 border-rose-300 dark:border-rose-800' }}">
                <div class="flex items-center gap-2">
                    @if ($testStatus === 'success')
                        <x-heroicon-s-check-circle class="w-5 h-5 text-emerald-500 flex-shrink-0" />
                    @else
                        <x-heroicon-s-x-circle class="w-5 h-5 text-rose-500 flex-shrink-0" />
                    @endif
                    <span>{{ $testMessage }}</span>
                </div>
                <button
                    wire:click="$set('testStatus', null)"
                    type="button"
                    class="text-xs opacity-70 hover:opacity-100 underline"
                >
                    Cerrar
                </button>
            </div>
        @endif

        <!-- Cuadrícula de Métricas de Extracción -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 pt-4 mt-4 border-t border-gray-100 dark:border-gray-800">
            <!-- Métrica 1: Procesados por IA -->
            <div class="p-3 rounded-xl bg-gray-50/70 dark:bg-gray-800/40 border border-gray-100 dark:border-gray-800/80">
                <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 mb-1">
                    <span>Tamizajes con IA</span>
                    <x-heroicon-m-sparkles class="w-4 h-4 text-emerald-500" />
                </div>
                <div class="text-xl font-bold text-gray-900 dark:text-white">
                    {{ $data['aiCount'] }}
                    <span class="text-xs font-normal text-gray-500 dark:text-gray-400">
                        ({{ $data['aiPercentage'] }}%)
                    </span>
                </div>
            </div>

            <!-- Métrica 2: Respaldo Programático -->
            <div class="p-3 rounded-xl bg-gray-50/70 dark:bg-gray-800/40 border border-gray-100 dark:border-gray-800/80">
                <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 mb-1">
                    <span>Modo Respaldo</span>
                    <x-heroicon-m-document-text class="w-4 h-4 text-gray-400" />
                </div>
                <div class="text-xl font-bold text-gray-900 dark:text-white">
                    {{ $data['programmaticCount'] }}
                </div>
            </div>

            <!-- Métrica 3: Total Registros -->
            <div class="p-3 rounded-xl bg-gray-50/70 dark:bg-gray-800/40 border border-gray-100 dark:border-gray-800/80">
                <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 mb-1">
                    <span>Total Evaluados</span>
                    <x-heroicon-m-users class="w-4 h-4 text-indigo-500" />
                </div>
                <div class="text-xl font-bold text-gray-900 dark:text-white">
                    {{ $data['totalScreenings'] }}
                </div>
            </div>

            <!-- Métrica 4: Último Análisis IA -->
            <div class="p-3 rounded-xl bg-gray-50/70 dark:bg-gray-800/40 border border-gray-100 dark:border-gray-800/80">
                <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 mb-1">
                    <span>Último Análisis IA</span>
                    <x-heroicon-m-clock class="w-4 h-4 text-amber-500" />
                </div>
                <div class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                    @if ($data['lastAiScreening'])
                        {{ $data['lastAiScreening']->created_at->diffForHumans() }}
                    @else
                        <span class="text-gray-400 font-normal">Sin registros aún</span>
                    @endif
                </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
