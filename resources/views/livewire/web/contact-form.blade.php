<div>
    <div x-data="{ success: @entangle('success') }" class="w-full">

        <!-- FORM -->
        <form wire:submit.prevent="submit"
            x-show="!success"
            x-transition.opacity.duration.500ms
            class="space-y-6">

            {{-- HONEYPOT --}}
            <input type="hidden" wire:model="bairro">
            <input type="text" class="hidden" wire:model="cidade">

            <!-- GRID -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Nome -->
                <div>
                    <input 
                        type="text"
                        wire:model="nome"
                        placeholder="Seu Nome"
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 
                            focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                            transition duration-200
                            @error('nome') border-red-500 @enderror"
                    >
                    @error('nome')
                        <span class="text-red-600 text-sm mt-1 block">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <input 
                        type="email"
                        wire:model="email"
                        placeholder="Seu E-mail"
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 
                            focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                            transition duration-200
                            @error('email') border-red-500 @enderror"
                    >
                    @error('email')
                        <span class="text-red-600 text-sm mt-1 block">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <!-- Mensagem -->
                <div class="md:col-span-2">
                    <textarea
                        wire:model="mensagem"
                        rows="5"
                        placeholder="Mensagem"
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 
                            focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                            transition duration-200
                            @error('mensagem') border-red-500 @enderror"
                    ></textarea>

                    @error('mensagem')
                        <span class="text-red-600 text-sm mt-1 block">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <!-- Botão -->
                <div class="md:col-span-2">
                    <button 
                        type="submit"
                        wire:loading.attr="disabled"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold 
                            py-3 px-6 rounded-lg shadow-md
                            transition duration-200
                            disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <span wire:loading.remove>
                            Enviar Mensagem
                        </span>

                        <span wire:loading class="flex justify-center items-center gap-2">
                            <svg class="animate-spin h-5 w-5"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24">
                                <circle class="opacity-25"
                                        cx="12"
                                        cy="12"
                                        r="10"
                                        stroke="currentColor"
                                        stroke-width="4"></circle>
                                <path class="opacity-75"
                                    fill="currentColor"
                                    d="M4 12a8 8 0 018-8v8H4z"></path>
                            </svg>
                            Enviando...
                        </span>
                    </button>
                </div>

            </div>
        </form>

        <!-- SUCESSO -->
        <div x-show="success"
            x-transition.opacity.duration.500ms
            class="mt-6 p-4 rounded-lg bg-green-100 border border-green-300 text-green-800">
            ✅ Mensagem enviada com sucesso! 
            <br>
            Entraremos em contato em breve.
        </div>

    </div>
</div>