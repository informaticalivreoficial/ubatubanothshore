@props(['property'])

<a href="{{route('web.property',['slug' => $property->slug])}}"
   class="group block bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1">

    {{-- Imagem --}}
    <div class="relative aspect-[4/3] bg-gray-100">
        <img
            src="{{ $property->cover() }}"
            alt="{{ $property->title }}"
            class="w-full h-full object-cover"
            loading="lazy">

        {{-- Bairro --}}
        @if ($property->neighborhood)
            <div class="absolute top-3 left-3">
                <span class="bg-blue-600 text-white font-bold text-xs px-3 py-1 rounded-full shadow-sm">
                    {{ $property->neighborhood }}
                </span>
            </div>
        @endif

        {{-- Badge venda ou aluguel --}}
        <div class="absolute top-3 right-3">
            <span class="bg-blue-400 text-white text-xs px-2 py-1 rounded-full">
                @if($property->reviews_count > 0)
                    ★ {{ number_format($property->reviews_avg_rating, 1) }}
                @else
                    Novo
                @endif
            </span>
        </div>
    </div>

    {{-- Conteúdo --}}
    <div class="p-4">

        <h3 class="text-xl font-bold line-clamp-2">
            {{ $property->title }}
        </h3>

        {{-- Headline --}}
        @if($property->headline)
            <p class="text-sm text-gray-500 mt-1 line-clamp-2">
                {{ $property->headline }}
            </p>
        @endif

        {{-- Dados principais --}}
        <div class="mt-3 flex flex-wrap gap-3 text-sm text-gray-600">
            @if($property->capacity)
                <span>{{ $property->capacity }} hóspedes</span>
            @endif

            @if($property->dormitories)
                <span>{{ $property->dormitories }} quarto</span>
            @endif

            @if($property->bathrooms)
                <span>{{ $property->bathrooms }} banheiro</span>
            @endif

            @if($property->garage)
                <span>{{ $property->garage }} vaga{{ $property->garage > 1 ? 's' : '' }}</span>
            @endif
        </div>

        <ul class="mt-3 flex flex-wrap gap-3">
            @if($property->cozinha == true)
                <li class="border rounded-xl border-slate-400 bg-slate-100 py-1 px-2">Cozinha</li>
            @endif
            @if($property->ar_condicionado == true)
                <li class="border rounded-xl border-slate-400 bg-slate-100 py-1 px-2">Ar condicionado</li>
            @endif
            @if($property->wifi == true)
                <li class="border rounded-xl border-slate-400 bg-slate-100 py-1 px-2">Wifi grátis</li>
            @endif
            @if($property->adequado_criancas == true)
                <li class="border rounded-xl border-slate-400 bg-slate-100 py-1 px-2">Adequado para crianças</li>
            @endif
        </ul>
    </div>
</a>