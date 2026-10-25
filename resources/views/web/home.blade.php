@extends('web.layouts.app')

@section('title', 'Início — ' . $configuracoes->app_name)

@section('content')

    <section class="relative min-h-screen flex items-center justify-center">
        {{-- Background image --}}
        <div class="absolute inset-0 z-0">
            <img 
                src="{{ $configuracoes->getheadersite() }}"
                alt="{{ $configuracoes->app_name }}"
                class="w-full h-full object-cover"/>
        </div>

        {{-- Hero content --}}
        <div class="relative z-10 text-center px-6 max-w-4xl mx-auto">
            <h1 class="fade-up-delay-1 font-display text-5xl md:text-7xl font-bold text-white leading-tight mb-4">
                {{ $configuracoes->app_name }}
            </h1>
            <p class="fade-up-delay-2 text-white/80 text-lg font-light mb-12">
                {{ $configuracoes->information }}
            </p>

            {{-- Search bar --}}
            <form
                action="{{ route('web.property.search') }}"
                method="GET"
                x-data="searchCalendar({ guests: {{ max(1, (int) request('guests', 1)) }} })"
                class="fade-up-delay-3 mx-auto mt-10 w-full max-w-4xl text-left"
            >
                <div class="rounded-[22px] border border-white/60 bg-white/95 p-5 shadow-2xl backdrop-blur-xl sm:p-6">
                    <div class="mb-5 flex flex-wrap items-end justify-between gap-3">
                        <div>
                            <p class="text-[10px] font-semibold uppercase tracking-[0.24em] text-accent">
                                Planeje sua estadia
                            </p>
                            <h2 class="mt-1 font-heading text-xl font-semibold text-primary sm:text-2xl">
                                Encontre o imóvel ideal
                            </h2>
                        </div>
                        <p class="text-xs text-slate-500">
                            Disponibilidade e valores em tempo real
                        </p>
                    </div>

                    <div class="grid grid-cols-1 gap-3 md:grid-cols-2 lg:grid-cols-[1fr_1fr_0.85fr_auto] lg:items-end">
                        {{-- Check-in --}}
                        <div class="space-y-1.5">
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
                                    id="home-check-in"
                                    name="check_in"
                                    x-ref="checkin"
                                    value="{{ request('check_in') }}"
                                    placeholder="Selecione a entrada"
                                    autocomplete="off"
                                    class="h-12 w-full rounded-xl border border-sand bg-cream pl-10 pr-3 text-sm font-medium text-primary outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/10"
                                >
                            </div>
                        </div>

                        {{-- Check-out --}}
                        <div class="space-y-1.5">
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
                                    id="home-check-out"
                                    name="check_out"
                                    x-ref="checkout"
                                    value="{{ request('check_out') }}"
                                    placeholder="Selecione a saída"
                                    autocomplete="off"
                                    class="h-12 w-full rounded-xl border border-sand bg-cream pl-10 pr-3 text-sm font-medium text-primary outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/10"
                                >
                            </div>
                        </div>

                        {{-- Hóspedes --}}
                        <div class="space-y-1.5">
                            <span class="block px-1 text-[11px] font-semibold uppercase tracking-[0.14em] text-slate-500">
                                Hóspedes
                            </span>
                            <div class="flex h-12 items-center justify-between rounded-xl border border-sand bg-cream px-2.5">
                                <button
                                    type="button"
                                    @click="decrementGuests"
                                    class="flex h-8 w-8 items-center justify-center rounded-lg text-lg leading-none text-primary/50 transition hover:bg-white hover:text-primary"
                                    aria-label="Diminuir hóspedes"
                                >
                                    −
                                </button>
                                <span class="text-sm font-semibold text-primary" x-text="guests"></span>
                                <button
                                    type="button"
                                    @click="incrementGuests"
                                    class="flex h-8 w-8 items-center justify-center rounded-lg text-lg leading-none text-primary/50 transition hover:bg-white hover:text-primary"
                                    aria-label="Aumentar hóspedes"
                                >
                                    +
                                </button>
                            </div>
                            <input
                                type="hidden"
                                name="guests"
                                value="{{ max(1, (int) request('guests', 1)) }}"
                                :value="guests"
                            >
                        </div>

                        {{-- Botão --}}
                        <button
                            type="submit"
                            class="inline-flex h-12 items-center justify-center gap-2 rounded-xl bg-primary px-6 text-sm font-semibold text-white transition hover:bg-navy focus:outline-none focus:ring-2 focus:ring-primary/30 focus:ring-offset-2"
                        >
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Buscar imóveis
                        </button>
                    </div>
                </div>
            </form>

        </div>

        {{-- Scroll indicator --}}
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 z-10 animate-bounce">
            <svg class="w-6 h-6 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 9l-7 7-7-7"/>
            </svg>
        </div>
    </section>    

    {{-- ============================
         PROPERTIES SECTION
    ============================= --}}
    @if ($properties->count() > 0)
        <section class="py-20 px-6">
            <div class="max-w-7xl mx-auto">

                {{-- Section header --}}
                <div class="mb-12 flex items-end justify-between">
                    <div>
                        <p class="text-accent text-xs font-medium tracking-[0.2em] uppercase mb-2">Explore</p>
                        <h2 class="font-display text-4xl font-semibold text-primary">Nossos principais imóveis</h2>
                    </div>
                    <a href="{{ route('web.properties') }}"
                    class="hidden md:inline-flex items-center gap-2 text-sm font-medium text-primary/60 hover:text-accent transition-colors group">
                        Ver todos imóveis
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>

                {{-- Properties grid --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($properties as $property)
                        @include('web.components.property-card', ['property' => $property])
                    @endforeach
                </div>

                {{-- CTA button --}}            
                <div class="mt-12 text-center">
                    <a href="{{ route('web.properties') }}"
                    class="inline-flex items-center gap-2 border border-slate-600 text-primary  px-8 py-3 rounded-xl text-xl font-medium transition-all duration-300">
                        Explorar todos os imóveis
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
                            
            </div>        
        </section>
    @endif
@endsection
