<div>
    <section class="py-10 px-6">
        <div class="max-w-7xl mx-auto">

            <h1 class="text-2xl font-bold text-gray-900 mb-4">
                Propriedades
            </h1>

            {{-- Filtros --}}
            <div class="mb-12 flex flex-wrap items-center gap-2">

                {{-- TODOS --}}
                <button
                    wire:click="setNeighborhood(null)"
                    class="px-4 py-1.5 rounded-full border text-sm font-medium transition
                    {{ $activeNeighborhood === null 
                        ? 'bg-blue-600 text-white border-blue-600' 
                        : 'bg-white text-gray-800 border-gray-300 hover:border-gray-500' }}">

                    Todos ({{ $this->totalGeral }})
                </button>

                {{-- BAIRROS --}}
                @foreach($this->neighborhoods as $bairro)
                    <button
                        wire:click="setNeighborhood('{{ $bairro->neighborhood }}')"
                        class="px-4 py-1.5 rounded-full border text-sm font-medium transition
                        {{ $activeNeighborhood === $bairro->neighborhood 
                            ? 'bg-blue-600 text-white border-blue-600' 
                            : 'bg-white text-gray-800 border-gray-300 hover:border-gray-500' }}">

                        {{ $bairro->neighborhood }} ({{ $bairro->total }})
                    </button>
                @endforeach

            </div>

            {{-- Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($this->properties as $property)
                    @include('web.components.property-card', ['property' => $property])
                @empty
                    <p class="col-span-full text-center text-gray-500">
                        Nenhuma propriedade encontrada.
                    </p>
                @endforelse
            </div>

            {{-- Botão Carregar Mais --}}
            @if($this->properties->count() < $this->total)
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
