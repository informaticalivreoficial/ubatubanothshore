@extends('web.layouts.app')

@section('title', 'Início — Catete Apartments')

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
            <div class="fade-up-delay-3 search-bar rounded-2xl p-4 shadow-2xl max-w-3xl mx-auto">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-3 items-end">
                    {{-- Check-in --}}
                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-medium text-primary/60 uppercase tracking-wider px-1">Check-in</label>
                        <input type="date"
                               class="w-full bg-cream border border-sand rounded-xl px-3 py-2.5 text-sm text-primary focus:outline-none focus:ring-2 focus:ring-accent/40 focus:border-accent transition-all"
                               name="check_in">
                    </div>

                    {{-- Check-out --}}
                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-medium text-primary/60 uppercase tracking-wider px-1">Check-out</label>
                        <input type="date"
                               class="w-full bg-cream border border-sand rounded-xl px-3 py-2.5 text-sm text-primary focus:outline-none focus:ring-2 focus:ring-accent/40 focus:border-accent transition-all"
                               name="check_out">
                    </div>

                    {{-- Guests --}}
                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-medium text-primary/60 uppercase tracking-wider px-1">Hóspedes</label>
                        <div class="flex items-center gap-2 bg-cream border border-sand rounded-xl px-3 py-2.5">
                            <button type="button" class="text-primary/40 hover:text-primary transition-colors w-5 h-5 flex items-center justify-center text-lg leading-none" id="guests-minus">−</button>
                            <span class="flex-1 text-center text-sm font-medium text-primary" id="guests-count">1</span>
                            <button type="button" class="text-primary/40 hover:text-primary transition-colors w-5 h-5 flex items-center justify-center text-lg leading-none" id="guests-plus">+</button>
                        </div>
                    </div>

                    {{-- Search button --}}
                    <button
                        class="bg-primary hover:bg-primary/90 text-white text-sm font-medium px-6 py-2.5 rounded-xl transition-all hover:shadow-lg hover:-translate-y-0.5 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        Procurar
                    </button>
                </div>
            </div>
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

@push('scripts')
    <script>
        
    </script>
@endpush
