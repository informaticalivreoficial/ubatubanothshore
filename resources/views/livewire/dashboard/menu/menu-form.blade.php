<div>  
    <form wire:submit.prevent="save" class="space-y-6">
        <div class="p-6 bg-white rounded-xl shadow-xl">

            <h2 class="text-2xl font-extrabold text-gray-900 mb-6 border-b pb-3">
                {{ $this->modalTitle }}
            </h2>

            <!-- GRID PRINCIPAL -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Título -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Título do Link
                    </label>
                    <input 
                        type="text"
                        wire:model.defer="title"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm"
                    >
                    @error('title') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                </div>

                <!-- Tipo -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Tipo
                    </label>
                    <select 
                        wire:model.live="type"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm"
                    >
                        <option value="">Selecione</option>
                        <option value="pagina">Página</option>
                        <option value="url">URL Externa</option>
                    </select>
                    @error('type') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                </div>

                <!-- Target -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Destino
                    </label>
                    <select 
                        wire:model.defer="target"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm"
                    >
                        <option value="1">Nova Janela</option>
                        <option value="0">Mesma Janela</option>
                    </select>
                </div>

                <!-- SE FOR PÁGINA -->
                @if($type === 'pagina')
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            Selecionar Página
                        </label>
                        <select 
                            wire:model.defer.number="post"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm"
                        >
                            <option value="">Selecione</option>
                            @foreach($pages as $page)
                                <option value="{{ $page->id }}">
                                    {{ $page->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <!-- SE FOR URL -->
                @if($type === 'url')
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            Link da URL
                        </label>
                        <input 
                            type="text"
                            wire:model.defer="url"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm"
                        >
                    </div>
                @endif

                <!-- Status -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Exibir?
                    </label>
                    <select 
                        wire:model.defer.number="status"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm"
                    >
                        <option value="1">Sim</option>
                        <option value="0">Não</option>
                    </select>
                </div>

            </div>

            <!-- Botão -->
            <div class="mt-8 pt-4 border-t flex justify-end">
                <button 
                    type="submit"
                    wire:loading.attr="disabled"
                    class="px-6 py-2.5 rounded-lg text-white bg-blue-600 hover:bg-blue-700"
                >
                    {{ ($id ? 'Editar Link' : 'Salvar Link') }}
                </button>
                <button 
                    @click="open = false"
                    class="px-4 py-2 mx-2 bg-gray-200 hover:bg-gray-300 rounded"
                >
                    Fechar
                </button>                
            </div>

        </div>
    </form>
</div>

