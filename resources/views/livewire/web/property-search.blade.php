<div>
    <section class="py-10 px-6">
        
        <div class="fade-up-delay-3 search-bar p-4 max-w-7xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-3 items-end">
                {{-- Check-in --}}
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-medium text-primary/60 uppercase tracking-wider px-1">Check-in</label>
                    <input type="date" wire:model="check_in"
                        class="w-full bg-cream border border-sand rounded-xl px-3 py-2.5 text-sm text-primary focus:outline-none focus:ring-2 focus:ring-accent/40 focus:border-accent transition-all">
                </div>

                {{-- Check-out --}}
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-medium text-primary/60 uppercase tracking-wider px-1">Check-out</label>
                    <input type="date" wire:model="check_out"
                        class="w-full bg-cream border border-sand rounded-xl px-3 py-2.5 text-sm text-primary focus:outline-none focus:ring-2 focus:ring-accent/40 focus:border-accent transition-all">
                </div>

                {{-- Guests --}}
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-medium text-primary/60 uppercase tracking-wider px-1">Hóspedes</label>
                    <div class="flex items-center gap-2 bg-cream border border-sand rounded-xl px-3 py-2.5">
                        <button type="button" wire:click="decrementGuests" class="text-primary/40 hover:text-primary transition-colors w-5 h-5 flex items-center justify-center text-lg leading-none">−</button>
                        <span class="flex-1 text-center text-sm font-medium text-primary">{{ $guests }}</span>
                        <button type="button" wire:click="incrementGuests" class="text-primary/40 hover:text-primary transition-colors w-5 h-5 flex items-center justify-center text-lg leading-none">+</button>
                    </div>
                </div>

                {{-- Search button --}}
                <button wire:click="search"
                        class="bg-white border border-blue-500 text-blue-500 text-sm font-medium px-6 py-2.5 rounded-xl transition-all hover:bg-[#f8f8f8] flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Procurar
                </button>
            </div>

            {{-- Resultados --}}
            <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                @forelse($properties as $property)
                    @include('web.components.property-card', ['property' => $property])
                @empty
                    <p class="text-gray-500 col-span-3">Nenhuma propriedade encontrada para esses filtros.</p>
                @endforelse
            </div>

            {{-- Botão Carregar Mais --}}
            @if($properties->count() >= $perPage)
                <div class="mt-12 text-center">
                    <button
                        wire:click="loadMore"
                        wire:loading.attr="disabled"
                        class="inline-flex items-center gap-2 border border-slate-600 px-8 py-3 rounded-xl text-lg font-medium transition hover:bg-slate-100">

                        <span wire:loading.remove>
                            Carregar mais imóveis
                        </span>

                        <span wire:loading>
                            Carregando...
                        </span>
                    </button>
                </div>
            @endif

        </div>
        
    </section>
</div>
