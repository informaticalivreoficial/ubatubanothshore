<div>
    <section class="px-6 py-10">
        <div
            x-data="searchCalendar({ livewire: true })"
            class="fade-up-delay-3 mx-auto max-w-7xl"
        >
            <div class="rounded-2xl border border-sand bg-white p-4 shadow-card">
                <div class="grid grid-cols-1 gap-3 md:grid-cols-2 lg:grid-cols-[1fr_1fr_0.85fr_auto] lg:items-end">
                    {{-- Check-in --}}
                    <div class="space-y-1.5" wire:ignore>
                        <span class="block px-1 text-[11px] font-semibold uppercase tracking-[0.14em] text-slate-500">
                            Check-in
                        </span>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-primary/45">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v14a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1z"/>
                                </svg>
                            </span>
                            <input
                                type="date"
                                id="property-search-check-in"
                                x-ref="checkin"
                                value="{{ $check_in }}"
                                placeholder="Selecione a entrada"
                                autocomplete="off"
                                class="h-12 w-full rounded-xl border border-sand bg-cream pl-10 pr-3 text-sm font-medium text-primary outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/10"
                            >
                        </div>
                    </div>

                    {{-- Check-out --}}
                    <div class="space-y-1.5" wire:ignore>
                        <span class="block px-1 text-[11px] font-semibold uppercase tracking-[0.14em] text-slate-500">
                            Check-out
                        </span>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-primary/45">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v14a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1z"/>
                                </svg>
                            </span>
                            <input
                                type="date"
                                id="property-search-check-out"
                                x-ref="checkout"
                                value="{{ $check_out }}"
                                placeholder="Selecione a saída"
                                autocomplete="off"
                                class="h-12 w-full rounded-xl border border-sand bg-cream pl-10 pr-3 text-sm font-medium text-primary outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/10"
                            >
                        </div>
                    </div>

                    {{-- Guests --}}
                    <div class="space-y-1.5">
                        <span class="block px-1 text-[11px] font-semibold uppercase tracking-[0.14em] text-slate-500">
                            Hóspedes
                        </span>
                        <div class="flex h-12 items-center justify-between rounded-xl border border-sand bg-cream px-2.5">
                            <button
                                type="button"
                                wire:click="decrementGuests"
                                class="flex h-8 w-8 items-center justify-center rounded-lg text-lg leading-none text-primary/50 transition hover:bg-white hover:text-primary"
                                aria-label="Diminuir hóspedes"
                            >
                                −
                            </button>
                            <span class="text-sm font-semibold text-primary">{{ $guests }}</span>
                            <button
                                type="button"
                                wire:click="incrementGuests"
                                class="flex h-8 w-8 items-center justify-center rounded-lg text-lg leading-none text-primary/50 transition hover:bg-white hover:text-primary"
                                aria-label="Aumentar hóspedes"
                            >
                                +
                            </button>
                        </div>
                    </div>

                    {{-- Search button --}}
                    <button
                        wire:click="search"
                        class="inline-flex h-12 items-center justify-center gap-2 rounded-xl bg-primary px-6 text-sm font-semibold text-white transition hover:bg-navy focus:outline-none focus:ring-2 focus:ring-primary/30 focus:ring-offset-2"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Buscar imóveis
                    </button>
                </div>
            </div>

            {{-- Resultados --}}
            <div class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-3">
                @forelse($properties as $property)
                    @include('web.components.property-card', ['property' => $property])
                @empty
                    <p class="col-span-3 text-gray-500">Nenhuma propriedade encontrada para esses filtros.</p>
                @endforelse
            </div>

            {{-- Botão Carregar Mais --}}
            @if($properties->count() >= $perPage)
                <div class="mt-12 text-center">
                    <button
                        wire:click="loadMore"
                        wire:loading.attr="disabled"
                        class="inline-flex items-center gap-2 rounded-xl border border-slate-600 px-8 py-3 text-lg font-medium transition hover:bg-slate-100"
                    >
                        <span wire:loading.remove>Carregar mais imóveis</span>
                        <span wire:loading>Carregando...</span>
                    </button>
                </div>
            @endif
        </div>
    </section>
</div>
