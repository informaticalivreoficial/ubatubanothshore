@extends('web.layouts.app')

@section('content')

<div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8 lg:py-8">

    <nav class="mb-4 flex items-center gap-2 text-sm text-gray-500" aria-label="Breadcrumb">
        <a href="{{ route('web.home') }}" class="transition-colors hover:text-blue-700">Início</a>
        <span aria-hidden="true">/</span>
        <a href="{{ route('web.properties') }}" class="transition-colors hover:text-blue-700">Imóveis</a>
        <span aria-hidden="true">/</span>
        <span class="truncate text-gray-700">{{ $property->title }}</span>
    </nav>

    <div class="relative overflow-hidden rounded-2xl border border-gray-200 bg-white p-2 shadow-sm">
        <div class="relative">
            <div class="grid grid-cols-1 gap-2 sm:h-[420px] sm:grid-cols-4">
                <div
                    class="relative h-[260px] cursor-pointer overflow-hidden rounded-xl bg-gray-200 sm:col-span-2 sm:row-span-2 sm:h-full"
                    x-data
                    x-on:click="$dispatch('open-gallery', { index: 0 })">
                    <img
                        src="{{ $property->cover() }}"
                        alt="{{ $property->title }}"
                        class="h-full w-full object-cover transition-transform duration-500 hover:scale-105"
                    />
                </div>

                @foreach($property->images->take(4) as $index => $image)
                    <div
                        class="relative hidden h-[206px] cursor-pointer overflow-hidden rounded-xl bg-gray-200 sm:block"
                        x-data
                        x-on:click="$dispatch('open-gallery', { index: {{ $loop->index + 1 }} })">
                        <img
                            src="{{ $image->url_image }}"
                            alt="{{ $property->title }}"
                            class="h-full w-full object-cover transition-transform duration-500 hover:scale-105"
                        />
                    </div>
                @endforeach
            </div>

            <!-- Badge de localização -->
            @if ($property->neighborhood)
                <div class="absolute top-4 left-4 bg-[#337bbc] text-white px-4 py-2 rounded-lg text-sm font-medium shadow-lg">
                    {{ $property->neighborhood }}
                </div>
            @endif            

            <!-- Galeria modal (só o botão + modal, sem grid) -->
            <livewire:web.image-gallery :images="$property->images->toArray()" />
        </div>

        

        <!-- Conteúdo do imóvel -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Coluna principal -->
            <div class="md:col-span-2">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-blue-700">
                        {{ $property->type }}
                    </span>

                    @if($property->highlight)
                        <span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-amber-700">
                            Destaque
                        </span>
                    @endif
                </div>

                <h1 class="mt-3 text-2xl font-semibold text-gray-900 sm:text-3xl">
                    {{ $property->title }}
                </h1>

                <p class="mt-2 flex items-center gap-1.5 text-gray-600">
                    <svg class="h-4 w-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span>
                        {{ $property->neighborhood }}
                        @if($property->neighborhood && $property->city)
                            •
                        @endif
                        @if($property->city)
                            {{ $property->city }}, {{ $property->state }}
                        @endif
                    </span>
                </p>

                @if($property->reviews_count > 0)
                    <div class="mt-3 flex items-center gap-2 text-sm">
                        <span class="flex items-center gap-1 font-semibold text-gray-900">
                            <span class="text-yellow-500">★</span>
                            {{ number_format($property->reviews_avg_rating, 1) }}
                        </span>
                        <span class="text-gray-400">•</span>
                        <span class="text-gray-500">{{ $property->reviews_count }} avaliação(ões)</span>
                    </div>
                @endif

                <div class="mt-5 grid grid-cols-2 gap-3 border-y border-gray-200 py-4 sm:grid-cols-4">
                    <div class="rounded-lg bg-gray-50 px-3 py-3">
                        <p class="text-xs text-gray-500">Hóspedes</p>
                        <p class="mt-1 font-semibold text-gray-900">
                            {{ $property->capacity }}
                            @if ($property->aditional_person)
                                <span class="text-xs font-normal text-gray-500">+{{ $property->aditional_person }}</span>
                            @endif
                        </p>
                    </div>

                    <div class="rounded-lg bg-gray-50 px-3 py-3">
                        <p class="text-xs text-gray-500">Quartos</p>
                        <p class="mt-1 font-semibold text-gray-900">{{ $property->dormitories }}</p>
                    </div>

                    <div class="rounded-lg bg-gray-50 px-3 py-3">
                        <p class="text-xs text-gray-500">Banheiros</p>
                        <p class="mt-1 font-semibold text-gray-900">{{ $property->bathrooms }}</p>
                    </div>

                    <div class="rounded-lg bg-gray-50 px-3 py-3">
                        <p class="text-xs text-gray-500">Vagas</p>
                        <p class="mt-1 font-semibold text-gray-900">{{ $property->garage }}</p>
                    </div>
                </div>

                @if($property->description)
                    <div class="mt-6 text-gray-600">
                        <h3 class="mb-2 text-lg font-semibold">Sobre este espaço</h3>
                        {!! $property->description !!}
                    </div>
                @endif

                @php
                        $amenidades = [
                            'acesso_praia' => 'Acesso à Praia',
                            'adequado_criancas' => 'Adequado para Crianças',
                            'adequado_idosos' => 'Adequado para Idosos',
                            'agua_quente' => 'Água Quente',
                            'aquecedor' => 'Aquecedor',
                            'ar_condicionado' => 'Ar Condicionado',
                            'areadelazer' => 'Área de Lazer',
                            'banheira' => 'Banheira',
                            'banheiro_privativo' => 'Banheiro Privativo',
                            'cafeteira' => 'Cafeteira',
                            'cama_casal' => 'Cama de Casal',
                            'cameras' => 'Câmeras de Segurança',
                            'churrasqueira' => 'Churrasqueira',
                            'condominiofechado' => 'Condomínio Fechado',
                            'cozinha' => 'Cozinha',
                            'elevador' => 'Elevador',
                            'espaco_fitness' => 'Espaço Fitness',
                            'estacionamento' => 'Estacionamento',
                            'fechadura_eletronica' => 'Fechadura Eletrônica',
                            'ferro_passar' => 'Ferro de Passar',
                            'fornopizza' => 'Forno de Pizza',
                            'frigobar' => 'Frigobar',
                            'garagem' => 'Garagem',
                            'geladeira' => 'Geladeira',
                            'interfone' => 'Interfone',
                            'jardim' => 'Jardim',
                            'lareira' => 'Lareira',
                            'lavabo' => 'Lavabo',
                            'maquina_lavar' => 'Máquina de Lavar',
                            'mesa_refeicao' => 'Mesa de Refeição',
                            'mesa_trabalho' => 'Mesa de Trabalho',
                            'microondas' => 'Microondas',
                            'mobiliado' => 'Mobiliado',
                            'permiteanimais' => 'Permite Animais',
                            'piscina' => 'Piscina',
                            'portaria24hs' => 'Portaria 24h',
                            'pratos_talheres' => 'Pratos e Talheres',
                            'produtos_limpeza' => 'Produtos de Limpeza',
                            'roupa_cama' => 'Roupa de Cama',
                            'sauna' => 'Sauna',
                            'salaodejogos' => 'Salão de Jogos',
                            'secador_cabelo' => 'Secador de Cabelo',
                            'secadora' => 'Secadora',
                            'tv' => 'TV',
                            'tv_netflix' => 'TV com Netflix',
                            'ventilador_teto' => 'Ventilador de Teto',
                            'vista_para_mar' => 'Vista para o Mar',
                            'wifi' => 'Wi-Fi',
                        ];

                        $amenidadesAtivas = collect($amenidades)
                            ->filter(fn ($label, $campo) => $property->$campo);
                    @endphp

                    @if($amenidadesAtivas->isNotEmpty())
                        <div class="mt-6">
                            <h3 class="mb-3 text-lg font-semibold">Facilidades</h3>
                            <div class="grid grid-cols-2 gap-3">
                                @foreach($amenidadesAtivas as $campo => $label)
                                    <div class="flex items-center gap-2">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span>{{ $label }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                <hr class="border-gray-300 mt-8">

                <div class="max-w-3xl mx-auto font-sans mt-4">
                    <!-- Cabeçalho da seção de avaliações -->
                    @if($property->reviews_count > 0)
                        <h2 class="text-xl font-semibold text-gray-900">Avaliações</h2>
                        <span class="flex items-center gap-1">
                        <span class="text-yellow-500">⭐</span>
                        <span class="font-medium text-gray-900">{{ number_format($property->reviews_avg_rating, 1) }}</span>
                        <span class="text-gray-500">({{ $property->reviews_count }})</span>
                        </span>
                    @endif                    

                    <!-- Lista de avaliações -->
                    <div class="space-y-6">
                        @forelse($property->reviews as $review)
                            <div class="border-b border-gray-200 pb-6">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="text-yellow-400 text-lg">{{ str_repeat('⭐', $review->rating) }}</span>
                                    <span class="font-medium text-gray-900">· {{ $review->guest_name }} ·</span>
                                    <span class="text-gray-600">
                                        {{ $review->created_at->translatedFormat('F \d\e Y') }}
                                    </span>
                                </div>
                                
                                <div class="space-y-2 text-gray-700">
                                    <p>{{ $review->comment }}</p>
                                </div>
                            </div>
                        @empty
                            <p>Ainda não há avaliações para este imóvel.</p>
                        @endforelse                        
                    </div>                    
                </div>                

                @if($property->additional_notes)
                    <div class="mb-6 mt-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-3">Bom saber</h2>
                        <p class="text-gray-600">{!! nl2br(e($property->additional_notes)) !!}</p>
                    </div>
                @endif

                @if($property->politica_cancelamento)
                    <div class="mb-6 mt-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-3">Política de cancelamento</h2>
                        <p class="text-gray-600">{!! nl2br(e($property->politica_cancelamento)) !!}</p>
                    </div>
                @endif

            </div>

            <div class="md:col-span-1">
                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm md:sticky md:top-24">
                    <livewire:web.booking-form :$property />
                </div>
            </div>
        </div>
    </div>
</div>

@endsection