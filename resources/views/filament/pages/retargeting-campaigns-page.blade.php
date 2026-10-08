<x-filament-panels::page>
    @php
        $data = $this->getCampaignData();
        $logs = $data['logs'];
        $counts = $data['counts'];
    @endphp

    <div class="space-y-6">
        <!-- Banner Informativo del Embudo -->
        <div class="bg-gradient-to-r from-indigo-900 to-slate-900 text-white rounded-2xl p-6 shadow-md border border-indigo-700/50">
            <h2 class="text-xl font-bold mb-1">Fase 5: Retargeting y Automatización del Embudo</h2>
            <p class="text-sm text-indigo-200">
                Segmentación estratégica para prospectos con requerimiento visual que no compraron en sitio, guiándolos desde la concientización hasta la refracción clínica.
            </p>
        </div>

        <!-- Selector de Pestañas (Día 3, Día 15, Día 90) -->
        <div class="flex border-b border-gray-200 dark:border-gray-700 space-x-4">
            <button
                wire:click="$set('activeTab', 'day_3')"
                class="py-3 px-4 border-b-2 font-medium text-sm flex items-center gap-2 transition {{ $this->activeTab === 'day_3' ? 'border-emerald-500 text-emerald-600 dark:text-emerald-400 font-bold' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400' }}"
            >
                <span>📅 DÍA 3: Concientización y Catálogo</span>
                <span class="px-2 py-0.5 text-xs rounded-full bg-emerald-100 text-emerald-800 dark:bg-emerald-900/60 dark:text-emerald-300">
                    {{ $counts['day_3'] }}
                </span>
            </button>

            <button
                wire:click="$set('activeTab', 'day_15')"
                class="py-3 px-4 border-b-2 font-medium text-sm flex items-center gap-2 transition {{ $this->activeTab === 'day_15' ? 'border-amber-500 text-amber-600 dark:text-amber-400 font-bold' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400' }}"
            >
                <span>🏷️ DÍA 15: Cupón de Descuento (20%)</span>
                <span class="px-2 py-0.5 text-xs rounded-full bg-amber-100 text-amber-800 dark:bg-amber-900/60 dark:text-amber-300">
                    {{ $counts['day_15'] }}
                </span>
            </button>

            <button
                wire:click="$set('activeTab', 'day_90')"
                class="py-3 px-4 border-b-2 font-medium text-sm flex items-center gap-2 transition {{ $this->activeTab === 'day_90' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400 font-bold' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400' }}"
            >
                <span>🩺 DÍA 90: Examen Clínico Completo</span>
                <span class="px-2 py-0.5 text-xs rounded-full bg-indigo-100 text-indigo-800 dark:bg-indigo-900/60 dark:text-indigo-300">
                    {{ $counts['day_90'] }}
                </span>
            </button>
        </div>

        <!-- Lista de Prospectos para el Hito Seleccionado -->
        <div class="space-y-4">
            @forelse($logs as $item)
                @php
                    $client = $item->client;
                    $phone = $client ? \App\Models\Client::sanitizePhone($client->phone) : null;
                    $copy = $client ? $this->getCopyForStage($this->activeTab, $client) : '';
                    $waUrl = $phone ? 'https://api.whatsapp.com/send?phone=' . urlencode($phone) . '&text=' . urlencode($copy) : null;
                @endphp

                <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-200 dark:border-gray-700 shadow-sm">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                        <div class="space-y-2 flex-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <h3 class="text-base font-bold text-gray-900 dark:text-white">
                                    {{ $client?->full_name }}
                                </h3>
                                <span class="text-xs px-2 py-0.5 bg-gray-100 dark:bg-gray-700 rounded-md text-gray-600 dark:text-gray-300">
                                    {{ $client?->company?->name ?: 'Particular' }}
                                </span>
                                <span class="text-xs text-gray-500">
                                    Programado: {{ $item->scheduled_for->format('d/m/Y') }}
                                </span>

                                @if($item->status === 'sent')
                                    <span class="px-2 py-0.5 text-xs bg-emerald-50 text-emerald-700 border border-emerald-300 rounded-full">
                                        ✓ Enviado {{ $item->sent_at?->diffForHumans() }}
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 text-xs bg-amber-50 text-amber-700 border border-amber-300 rounded-full">
                                        ⏳ Pendiente
                                    </span>
                                @endif
                            </div>

                            <div class="p-3 bg-gray-50 dark:bg-gray-900/40 rounded-lg text-xs text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-800 font-mono whitespace-pre-line">
                                {{ $copy }}
                            </div>
                        </div>

                        <div class="flex flex-col gap-2 min-w-[200px]">
                            @if($waUrl)
                                <a
                                    href="{{ $waUrl }}"
                                    target="_blank"
                                    wire:click="markAsSent({{ $item->id }})"
                                    class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs rounded-lg transition shadow-sm"
                                >
                                    <x-heroicon-o-chat-bubble-left-right class="w-4 h-4" />
                                    Disparar WhatsApp
                                </a>
                            @else
                                <button disabled class="px-3 py-1.5 bg-gray-200 dark:bg-gray-700 text-gray-500 text-xs rounded-lg cursor-not-allowed">
                                    Sin teléfono registrado
                                </button>
                            @endif

                            @if($item->status !== 'sent')
                                <button
                                    wire:click="markAsSent({{ $item->id }})"
                                    class="px-3 py-1.5 text-xs text-gray-600 hover:text-gray-800 dark:text-gray-400 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700"
                                >
                                    Marcar como Enviado Manual
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white dark:bg-gray-800 rounded-xl p-12 text-center border border-gray-200 dark:border-gray-700">
                    <x-heroicon-o-calendar-days class="w-12 h-12 mx-auto text-gray-400 mb-3" />
                    <h4 class="text-base font-semibold text-gray-800 dark:text-gray-200">No hay prospectos pendientes en esta etapa</h4>
                    <p class="text-sm text-gray-500 mt-1">Los tamizajes importados programan automáticamente los hitos de seguimiento a los 3, 15 y 90 días.</p>
                </div>
            @endforelse

            <div class="mt-4">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
</x-filament-panels::page>
