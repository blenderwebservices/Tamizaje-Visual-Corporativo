<x-filament-panels::page>
    @php
        $data = $this->getViewData();
        $screenings = $data['screenings'];
        $totalScreenings = $data['totalScreenings'];
        $pendingCount = $data['pendingCount'];
        $sentCount = $data['sentCount'];
        $companies = $data['companies'];
    @endphp

    <div class="space-y-6">
        <!-- Tarjetas de Resumen Estadístico -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-200 dark:border-gray-700 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Tamizajes</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $totalScreenings }}</p>
                </div>
                <div class="w-12 h-12 bg-indigo-50 dark:bg-indigo-900/40 rounded-lg flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                    <x-heroicon-o-document-chart-bar class="w-6 h-6" />
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-amber-200 dark:border-amber-800/40 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-amber-600 dark:text-amber-400 font-semibold">Pendientes de Envío</p>
                    <p class="text-2xl font-bold text-amber-700 dark:text-amber-300 mt-1">{{ $pendingCount }}</p>
                </div>
                <div class="w-12 h-12 bg-amber-50 dark:bg-amber-900/30 rounded-lg flex items-center justify-center text-amber-600">
                    <x-heroicon-o-clock class="w-6 h-6" />
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-emerald-200 dark:border-emerald-800/40 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-emerald-600 dark:text-emerald-400 font-semibold">Enviados con Éxito</p>
                    <p class="text-2xl font-bold text-emerald-700 dark:text-emerald-300 mt-1">{{ $sentCount }}</p>
                </div>
                <div class="w-12 h-12 bg-emerald-50 dark:bg-emerald-900/30 rounded-lg flex items-center justify-center text-emerald-600">
                    <x-heroicon-o-check-circle class="w-6 h-6" />
                </div>
            </div>
        </div>

        <!-- Barra de Filtros y Búsqueda -->
        <div class="bg-white dark:bg-gray-800 p-4 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm flex flex-col md:flex-row gap-4 justify-between items-center">
            <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Buscar por nombre, teléfono o ID Sujeto..."
                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-emerald-500 focus:border-emerald-500 w-full sm:w-72"
                />

                <select
                    wire:model.live="filterStatus"
                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white"
                >
                    <option value="">Todos los Estados</option>
                    <option value="pending">Solo Pendientes</option>
                    <option value="sent">Solo Enviados</option>
                </select>

                <select
                    wire:model.live="filterCompany"
                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white"
                >
                    <option value="">Todas las Empresas</option>
                    @foreach($companies as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            @if($pendingCount > 0 && $this->filterStatus === 'pending')
                <button
                    wire:click="markAllVisibleAsSent"
                    wire:confirm="¿Deseas marcar todos los estudios visibles como enviados?"
                    class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-medium text-sm rounded-lg transition shadow-sm whitespace-nowrap"
                >
                    Marcar Visibles como Enviados
                </button>
            @endif
        </div>

        <!-- Lista de Colaboradores en Cola de Envío -->
        <div class="space-y-4">
            @forelse($screenings as $item)
                <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-200 dark:border-gray-700 shadow-sm transition hover:shadow-md">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                        <div class="space-y-2 max-w-2xl">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-800 dark:bg-indigo-900/60 dark:text-indigo-300">
                                    ID: {{ $item->subject_code ?: 'S/ID' }}
                                </span>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                                    {{ $item->client?->full_name }}
                                </h3>
                                @if($item->screening_status === 'pass')
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 dark:bg-emerald-900/60 dark:text-emerald-300">
                                        PASA
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-100 text-rose-800 dark:bg-rose-900/60 dark:text-rose-300">
                                        REMITIR
                                    </span>
                                @endif

                                @if($item->whatsapp_status === 'sent')
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-300">
                                        ✓ Enviado {{ $item->whatsapp_sent_at?->diffForHumans() }}
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-300">
                                        ⏳ Pendiente de Envío
                                    </span>
                                @endif
                            </div>

                            <div class="text-sm text-gray-600 dark:text-gray-300 flex flex-wrap gap-x-4 gap-y-1">
                                <span>📱 <strong>Teléfono:</strong> {{ $item->client?->phone ?: 'No registrado en formulario' }}</span>
                                <span>🏢 <strong>Empresa:</strong> {{ $item->company?->name ?: 'Particular' }}</span>
                                <span>🕒 <strong>Examen:</strong> {{ $item->exam_date?->format('d/m/Y H:i') }}</span>
                            </div>

                            <div class="p-3 bg-gray-50 dark:bg-gray-900/50 rounded-lg text-sm text-gray-700 dark:text-gray-300 border border-gray-100 dark:border-gray-800">
                                <p class="font-semibold text-xs text-gray-500 uppercase tracking-wider mb-1">Análisis Rápido Clínico:</p>
                                <p>{{ $item->quick_analysis_summary }}</p>
                            </div>
                        </div>

                        <!-- Botones de Acción Rápida -->
                        <div class="flex flex-col sm:flex-row lg:flex-col items-stretch sm:items-center gap-2">
                            @if(!empty($item->client?->phone))
                                <a
                                    href="{{ $item->whatsapp_url }}"
                                    target="_blank"
                                    wire:click="markAsSent({{ $item->id }})"
                                    class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm rounded-lg transition shadow-sm"
                                >
                                    <x-heroicon-o-chat-bubble-left-right class="w-5 h-5" />
                                    Enviar vía WhatsApp
                                </a>
                            @else
                                <button disabled class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-500 text-sm font-medium rounded-lg cursor-not-allowed">
                                    Sin teléfono registrado
                                </button>
                            @endif

                            <div class="flex items-center gap-2">
                                <a
                                    href="{{ $item->public_report_url }}"
                                    target="_blank"
                                    class="flex-1 text-center px-3 py-1.5 border border-indigo-300 dark:border-indigo-700 text-indigo-600 dark:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 text-xs font-medium rounded-lg transition"
                                >
                                    Ver Reporte Digital
                                </a>

                                @if($item->whatsapp_status === 'sent')
                                    <button
                                        wire:click="markAsPending({{ $item->id }})"
                                        class="px-2.5 py-1.5 text-xs text-gray-500 hover:text-gray-700 dark:text-gray-400 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700"
                                        title="Revertir a pendiente"
                                    >
                                        Revertir
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white dark:bg-gray-800 rounded-xl p-12 text-center border border-gray-200 dark:border-gray-700">
                    <x-heroicon-o-inbox class="w-12 h-12 mx-auto text-gray-400 mb-3" />
                    <h4 class="text-base font-semibold text-gray-800 dark:text-gray-200">No hay estudios en esta selección</h4>
                    <p class="text-sm text-gray-500 mt-1">Importa nuevos archivos PDF de SpotVision o ajusta los filtros de búsqueda.</p>
                </div>
            @endforelse

            <div class="mt-4">
                {{ $screenings->links() }}
            </div>
        </div>
    </div>
</x-filament-panels::page>
