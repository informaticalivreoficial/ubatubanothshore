<div>
    <div class="flex justify-center mt-10 mb-10 px-4">

    <div 
        x-data="{ rating: $wire.entangle('rating') }"
        class="w-full max-w-xl bg-white p-8 rounded-xl shadow-lg space-y-6"
    >

        <h2 class="text-2xl font-semibold text-center">
            Deixe sua avaliação e ajude-nos a melhorar.
        </h2>

        <!-- Estrelas -->
        <div class="flex justify-center gap-2">
            <template x-for="i in 5" :key="i">
                <svg 
                    @click="rating = i"
                    :class="i <= rating ? 'text-yellow-400 scale-110' : 'text-gray-300'"
                    class="w-10 h-10 cursor-pointer transition transform hover:scale-110"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                >
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967h4.173c.969 
                    0 1.371 1.24.588 1.81l-3.377 
                    2.455 1.287 3.966c.3.922-.755 
                    1.688-1.54 1.118L10 
                    13.347l-3.368 2.446c-.784.57-1.838-.196-1.539-1.118l1.287-3.966-3.377-2.455c-.783-.57-.38-1.81.588-1.81h4.173l1.285-3.967z"/>
                </svg>
            </template>
        </div>

        <!-- Texto da avaliação -->
        <p class="text-center text-gray-500 text-sm">
            <span x-text="rating"></span> de 5 estrelas
        </p>

        <!-- Comentário -->
        <div class="space-y-1">
            <textarea
                wire:model="comment"
                rows="4"
                class="w-full border border-gray-300 rounded-lg p-4 focus:ring-2 focus:ring-green-500 focus:border-green-500
                {{ $errors->has('comment') 
                ? 'border-red-500 focus:ring-red-500 focus:border-red-500' 
                : 'border-gray-300 focus:ring-green-500 focus:border-green-500' }}"
                
                placeholder="Conte como foi sua estadia..."
            ></textarea>
            @error('comment')
                <div class="text-sm text-red-500">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Botão -->
        <div class="flex justify-center">
            <button
                wire:click="save"
                class="bg-green-600 text-white px-8 py-3 rounded-lg hover:bg-green-700 transition"
            >
                Enviar avaliação
            </button>
        </div>
        

    </div>

</div>
</div>
