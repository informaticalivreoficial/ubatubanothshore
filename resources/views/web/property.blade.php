@extends('web.layouts.app')

@section('content')

<div class="max-w-7xl mx-auto px-6 lg:px-8 py-8 space-y-8">

    {{-- GALERIA --}}
    <div class="max-w-7xl mx-auto p-4">

        <!-- Galeria de fotos do imóvel -->
        <div class="relative">
            <!-- Grid de fotos -->
            <div class="grid grid-cols-4 gap-2 h-[400px] rounded-xl overflow-hidden">
                <!-- Foto principal -->
                <div 
                    class="col-span-2 row-span-2 relative bg-gray-200 cursor-pointer"
                    x-data
                    x-on:click="$dispatch('open-gallery', { index: 0 })">
                    <img 
                        src="{{ $property->cover() }}" 
                        alt="{{ $property->title }}"
                        class="w-full h-full object-cover"
                    />
                </div>
            
                <!-- Fotos secundárias -->
                @foreach($property->images->take(4) as $index => $image)
                    <div 
                        class="col-span-1 row-span-1 relative bg-gray-200 h-[200px] cursor-pointer group"
                        x-data
                        x-on:click="$dispatch('open-gallery', { index: {{ $loop->index + 1 }} })">
                        <img 
                            src="{{ $image->url_image }}" 
                            alt="{{ $property->title }}"
                            class="w-full h-full object-cover"
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
                <!-- Título -->
                <h2 class="text-2xl font-semibold text-gray-900 mb-2">
                    {{ $property->title }}
                </h2>
            
                <!-- Localização detalhada -->
                <p class="text-gray-600 mb-4">
                    <span class="font-medium">{{ $property->neighborhood }}</span> • {{ $property->city }}, {{ $property->state }}
                </p>

                <!-- Informações rápidas -->
                <div class="flex flex-wrap gap-4 py-4 border-y border-gray-200">
                    <div class="flex items-center gap-2">
                        <span>{{ $property->type }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span>{{ $property->capacity }} hóspedes</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4v10l8 4 8-4V7z"></path>
                        </svg>
                        <span>{{ $property->dormitories }} quarto</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                        <span>{{ $property->bathrooms }} banheiro</span>
                    </div>
                </div>

                <!-- Avaliação -->
                @if($property->reviews_count > 0)
                    <div class="flex items-center gap-2 mb-4 mt-4">
                        <div class="flex items-center">
                            <span class="text-yellow-500">★</span>
                            <span class="font-medium ml-1">{{ number_format($property->reviews_avg_rating, 1) }}</span>
                        </div>
                        <span class="text-gray-400">•</span>
                        <span class="text-gray-500">{{ $property->reviews_count }} comentário(s)</span>
                    </div>
                @endif                

                <!-- Descrição -->
                <div class="mt-6 text-gray-600">
                    <h3 class="font-semibold text-lg mb-2">Sobre este espaço</h3>            
                    {!! $property->description !!}
                </div>

                <!-- Comodidades -->
                <div class="mt-6">
                    <h3 class="font-semibold text-lg mb-3">Facilidades</h3>
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
                    @endphp
                    <div class="grid grid-cols-2 gap-3">
                        @foreach($amenidades as $campo => $label)
                            @if($property->$campo)
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span>{{ $label }}</span>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>

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

                <div class="mb-6 mt-4">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Bom saber</h2>
                    <p>{!! nl2br(e($property->additional_notes)) !!}</p>
                </div>

                <div class="mb-6 mt-4">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Política de cancelamento</h2>
                    <p>{!! nl2br(e($property->politica_cancelamento)) !!}</p>
                </div>

            </div>

            <!-- Sidebar - Card de reserva -->
            <div class="md:col-span-1">
                <div class="bg-white border border-gray-200 rounded-xl p-6 sticky top-6 shadow-sm">
                    <div class="mb-3 mt-2">
                        <p class="text-xs text-gray-500 mb-4">
                            Selecione as datas e o número de hóspedes para ver o preço total por noite
                        </p>
                        <p class="text-xs mb-4 font-bold text-blue-700">
                            Mínimo de {{ $property->min_nights }} diária(s)
                        </p>

                        <livewire:web.booking-form :$property />                    
                        
                    </div>                   
                </div>
            </div>
        </div>
    </div>
</div>

@endsection