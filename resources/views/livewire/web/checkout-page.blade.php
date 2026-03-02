<div>
    <div class="max-w-8xl mx-auto px-6 py-4">

        <div class="flex flex-col lg:flex-row gap-8">

            <!-- LEFT FORM -->
            <div class="w-full lg:w-3/5">

                <!-- Personal Info -->
                <h2 class="text-xl font-bold text-gray-900 mb-5">Informe os seus dados</h2>

                <div class="grid grid-cols-3 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nome Completo</label>
                        <input type="text" wire:model.defer="name" placeholder="Nome" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-500 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white"/>
                        @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>  
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">E-mail</label>
                        <input type="email" wire:model.defer="email" placeholder="E-mail" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-500 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white"/>
                        @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div> 
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Número de telefone</label>
                        <input type="tel" x-mask="(99) 99999-9999" wire:model.defer="phone" placeholder="(00) 00000-0000" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-500 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white"/>
                        @error('phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>             
                </div>

                <hr class="border-gray-200 mb-8"/>

                <!-- Special Requests -->
                <h2 class="text-xl font-bold text-gray-900 mb-1">Pedidos especiais (opcional)</h2>
                <p class="text-sm text-gray-500 mb-4">Deixe-nos saber se você tiver alguma solicitação ou comentário adicional</p>

                <textarea
                    placeholder="Sua mensagem"
                    rows="7"
                    wire:model.defer="notes"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-500 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white resize-y mb-5"
                ></textarea>

                <!-- Submit -->
                <button
                    wire:click="confirmReservation"
                    wire:loading.attr="disabled"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3.5 rounded-full text-sm transition-colors mb-3"
                >
                    Confirmar Reserva
                </button>

                <p class="text-xs text-center text-gray-500">
                Ao clicar neste botão, concordo com os
                <a href="#" class="text-blue-600 hover:underline">Política de Privacidade</a>
                e
                <a href="#" class="text-blue-600 hover:underline">Termos de serviço</a>
                </p>

            </div>

            <!-- RIGHT CARD -->
            <div class="w-full lg:w-2/5">
                <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm">

                <!-- Property -->
                <div class="flex gap-3 mb-5">
                    <img
                        src="{{ $property->cover() }}"
                        alt="{{ $property->title }}"
                        class="w-20 h-20 rounded-xl object-cover flex-shrink-0"
                    />
                    <div>
                        <p class="text-sm font-semibold text-gray-900 leading-snug">
                        {{ $property->title }}    
                        </p>  
                        <p class="text-xs font-bold text-blue-700">
                            Mínimo de {{ $property->min_nights }} diária(s)
                        </p>                  
                    </div>
                </div>

                <!-- Cancellation -->
                <div class="mb-5">
                    <p class="text-sm font-semibold text-gray-900 mb-1.5">Política de cancelamento</p>
                    <p class="text-sm text-gray-600">100% reembolso até 30 dias antes chegada</p>
                    <p class="text-sm text-gray-600">50% reembolso até 14 dias antes chegada</p>
                </div>

                <hr class="border-gray-200 mb-5"/>

                <!-- Trip Details -->
                <div 
                    x-data="{ editing: @entangle('editingTrip') }"
                    class="mb-5"
                >
                    <p class="text-sm font-semibold text-gray-900 mb-2">
                        Detalhes da estadia
                    </p>

                    <!-- VISUALIZAÇÃO -->
                    <div x-show="!editing">

                        <div class="flex justify-between items-center mb-1.5">
                            <span class="text-sm text-gray-600">
                                {{ \Carbon\Carbon::parse($check_in)->format('d/m/Y') }}
                                até
                                {{ \Carbon\Carbon::parse($check_out)->format('d/m/Y') }}
                            </span>

                            <button 
                                @click="editing = true"
                                type="button"
                                class="text-sm text-blue-600 hover:underline font-medium"
                            >
                                Editar
                            </button>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">
                                {{ $nights }} noite(s) e {{ $guests }} hóspede(s)
                            </span>

                            <button 
                                @click="editing = true"
                                type="button"
                                class="text-sm text-blue-600 hover:underline font-medium"
                            >
                                Editar
                            </button>
                        </div>
                    </div>

                    <!-- EDIÇÃO -->
                    <div x-show="editing" x-cloak class="space-y-3">

                        <div>
                            <label class="text-xs text-gray-500">Check-in</label>
                            <input type="date"
                                wire:model.live="check_in"
                                class="w-full border rounded-md px-3 py-2 text-sm">
                        </div>

                        <div>
                            <label class="text-xs text-gray-500">Check-out</label>
                            <input type="date"
                                wire:model.live="check_out"
                                class="w-full border rounded-md px-3 py-2 text-sm">
                        </div>

                        <div>
                            <label class="text-xs text-gray-500">Hóspedes</label>
                            <input type="number"
                                min="1"
                                wire:model.live="guests"
                                class="w-full border rounded-md px-3 py-2 text-sm">
                        </div>

                        <div class="flex justify-end gap-2 pt-2">
                            <button 
                                type="button"
                                @click="editing = false"
                                class="text-sm text-gray-600 hover:underline"
                            >
                                Cancelar
                            </button>

                            <button 
                                type="button"
                                @click="editing = false"
                                class="text-sm text-blue-600 font-medium hover:underline"
                            >
                                Salvar
                            </button>
                        </div>
                    </div>
                </div>

                <hr class="border-gray-200 mb-5"/>

            <!-- Price Details -->
            <div>
                <p class="text-sm font-semibold text-gray-900 mb-3">
                    Detalhes do preço
                </p>

                <div class="flex justify-between mb-2">
                    <span class="text-sm text-gray-600">
                        R$ {{ number_format($property->rental_value, 2, ',', '.') }}
                        x {{ $nights }} noite(s)
                    </span>

                    <span class="text-sm text-gray-700">
                        R$ {{ number_format($subtotal, 2, ',', '.') }}
                    </span>
                </div>

                {{-- Adicional por pessoa --}}
                @if($extraTotal > 0)
                    <div class="flex justify-between mb-2 text-orange-600">
                        <span class="text-sm">
                            {{ $extraGuests }} hóspede(s) extra(s)
                        </span>
                        <span class="text-sm">
                            R$ {{ number_format($extraTotal, 2, ',', '.') }}
                        </span>
                    </div>
                @endif

                <div class="flex justify-between mb-4">
                    <span class="text-sm text-gray-600">
                        Taxa de Limpeza
                    </span>

                    <span class="text-sm text-gray-700">
                        R$ {{ number_format($cleaning_fee, 2, ',', '.') }}
                    </span>
                </div>

                <hr class="border-gray-200 mb-4"/>

                <div class="flex justify-between mb-1.5">
                    <span class="text-sm font-bold text-gray-900">
                        Total
                    </span>

                    <span class="text-sm font-bold text-gray-900">
                        R$ {{ number_format($total, 2, ',', '.') }}
                    </span>
                </div>

                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">
                        Vencimento hoje
                    </span>

                    <span class="text-sm text-gray-700">
                        R$ {{ number_format($total, 2, ',', '.') }}
                    </span>
                </div>
            </div>

            </div>
        </div>

        </div>
    </div>
</div>
