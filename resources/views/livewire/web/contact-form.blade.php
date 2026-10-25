<div>
    <div x-data="{ success: @entangle('success') }" class="w-full">
        <form
            wire:submit.prevent="submit"
            x-show="!success"
            x-transition.opacity.duration.300ms
            class="space-y-5"
        >
            <input type="hidden" wire:model="bairro">
            <input type="text" class="hidden" wire:model="cidade" tabindex="-1" autocomplete="off" aria-hidden="true">

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div>
                    <label for="contact-name" class="mb-2 flex items-center gap-1.5 text-sm font-semibold text-gray-700">
                        Nome completo
                        <span class="text-red-500" aria-hidden="true">*</span>
                    </label>

                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </span>

                        <input
                            id="contact-name"
                            type="text"
                            wire:model="nome"
                            placeholder="Como podemos chamar você?"
                            autocomplete="name"
                            class="w-full rounded-lg border bg-white py-3 pl-11 pr-4 text-sm text-gray-700 placeholder-gray-400 transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 @error('nome') border-red-400 focus:border-red-500 focus:ring-red-500/20 @else border-gray-300 @enderror"
                        >
                    </div>

                    @error('nome')
                        <span class="mt-1.5 block text-xs font-medium text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="contact-email" class="mb-2 flex items-center gap-1.5 text-sm font-semibold text-gray-700">
                        E-mail
                        <span class="text-red-500" aria-hidden="true">*</span>
                    </label>

                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </span>

                        <input
                            id="contact-email"
                            type="email"
                            wire:model="email"
                            placeholder="voce@email.com"
                            autocomplete="email"
                            class="w-full rounded-lg border bg-white py-3 pl-11 pr-4 text-sm text-gray-700 placeholder-gray-400 transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 @error('email') border-red-400 focus:border-red-500 focus:ring-red-500/20 @else border-gray-300 @enderror"
                        >
                    </div>

                    @error('email')
                        <span class="mt-1.5 block text-xs font-medium text-red-600">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div>
                <label for="contact-message" class="mb-2 flex items-center gap-1.5 text-sm font-semibold text-gray-700">
                    Mensagem
                    <span class="text-red-500" aria-hidden="true">*</span>
                </label>

                <textarea
                    id="contact-message"
                    wire:model="mensagem"
                    rows="6"
                    placeholder="Conte um pouco sobre o que você precisa: imóvel, datas, número de hóspedes..."
                    class="w-full resize-y rounded-lg border bg-white px-4 py-3 text-sm leading-relaxed text-gray-700 placeholder-gray-400 transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 @error('mensagem') border-red-400 focus:border-red-500 focus:ring-red-500/20 @else border-gray-300 @enderror"
                ></textarea>

                @error('mensagem')
                    <span class="mt-1.5 block text-xs font-medium text-red-600">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex flex-col gap-4 border-t border-gray-100 pt-5 sm:flex-row sm:items-center sm:justify-between">
                <p class="flex items-start gap-2 text-xs leading-relaxed text-gray-500">
                    <svg class="mt-0.5 h-4 w-4 shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    Seus dados são utilizados apenas para responder a este atendimento.
                </p>

                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    class="inline-flex shrink-0 items-center justify-center gap-2 rounded-pill bg-navy px-6 py-3 text-sm font-bold text-white transition duration-200 hover:bg-navy-light hover:shadow-[0_8px_24px_rgba(15,39,68,0.25)] disabled:cursor-not-allowed disabled:opacity-60"
                >
                    <span wire:loading.remove class="inline-flex items-center gap-2">
                        Enviar mensagem
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                    </span>

                    <span wire:loading class="inline-flex items-center gap-2">
                        <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                        </svg>
                        Enviando...
                    </span>
                </button>
            </div>
        </form>

        <div
            x-show="success"
            x-cloak
            x-transition.opacity.duration.300ms
            class="rounded-xl border border-green-200 bg-green-50 p-6 text-center"
        >
            <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-green-100 text-green-600">
                <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </span>

            <h3 class="mt-4 font-heading text-lg font-bold text-green-900">Mensagem enviada</h3>
            <p class="mt-1 text-sm text-green-800">
                Recebemos o seu contato. Nossa equipe responderá em breve.
            </p>

            <button
                type="button"
                wire:click="$set('success', false)"
                class="mt-5 inline-flex items-center gap-2 rounded-pill border border-green-300 bg-white px-5 py-2.5 text-sm font-semibold text-green-800 transition hover:bg-green-100"
            >
                Enviar outra mensagem
            </button>
        </div>
    </div>
</div>
