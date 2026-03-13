<div>
    <div class="max-w-7xl mx-auto px-6 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- FORMULÁRIO --}}
        <div class="lg:col-span-2 space-y-8">

            {{-- DADOS DO TITULAR --}}
            <div class="bg-white rounded-2xl shadow-sm border p-6">

                <h2 class="text-xl font-semibold text-gray-800 mb-6">
                    Titular da reserva
                </h2>

                <form wire:submit="preparePayment" class="space-y-6">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Nome completo
                        </label>

                        <input
                            type="text"
                            wire:model="guest_name" @disabled(true)
                            class="w-full h-12 px-4 text-sm border border-gray-300 rounded-xl
                            focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                            placeholder-gray-400 transition"
                        >
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <div 
                            x-data="{ disabled: false }"
                            x-on:disable-cpf.window="disabled = true"
                            x-init="IMask($refs.cpf, { mask: '000.000.000-00' })">

                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                CPF
                            </label>

                            <input
                                x-ref="cpf"
                                type="text"
                                :disabled="disabled"
                                wire:model="guest_cpf"
                                class="w-full h-12 px-4 text-sm border border-gray-300 rounded-xl
                                focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                                placeholder-gray-400 transition"
                                placeholder="000.000.000-00"
                            >

                            @error('guest_cpf') 
                                <span class="text-red-500 text-xs validation-error">{{ $message }}</span> 
                            @enderror

                        </div>

                        <div x-data
                            x-init="IMask($refs.phone, { mask: '(00) 00000-0000' })">

                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Telefone / WhatsApp
                            </label>

                            <input
                                x-ref="phone"
                                type="text"
                                wire:model="guest_phone" @disabled(true)
                                class="w-full h-12 px-4 text-sm border border-gray-300 rounded-xl
                                focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                                placeholder-gray-400 transition"
                                placeholder="(00) 00000-0000"
                            >
                        </div>

                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Email
                        </label>

                        <input
                            type="email"
                            wire:model="guest_email" @disabled(true)
                            class="w-full h-12 px-4 text-sm border border-gray-300 rounded-xl
focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
placeholder-gray-400 transition"
                        >
                    </div>

                </form>

            </div>


            {{-- HÓSPEDES ADICIONAIS --}}
            @if($reservation->guests > 1)

            <div class="bg-white rounded-2xl shadow-sm border p-6">

                <h2 class="text-xl font-semibold text-gray-800 mb-6">
                    Outros hóspedes
                </h2>

                <div class="space-y-6">

                    @foreach($guests as $index => $guest)

                    <div class="border rounded-xl p-4 bg-gray-50">

                        <h3 class="font-medium text-gray-700 mb-4">
                            Hóspede {{ $index + 1 }}
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                            <div>
                                <input
                                    type="text"
                                    placeholder="Nome"
                                    wire:model="guests.{{ $index }}.name"
                                    class="w-full h-12 px-4 text-sm border border-gray-300 rounded-xl
                                    focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                                    placeholder-gray-400 transition"
                                >
                                @error('guests.' . $index . '.name')
                                    <span class="text-red-500 text-xs validation-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <input
                                    x-data
                                    x-init="IMask($el, { mask: '000.000.000-00' })"
                                    wire:model="guests.{{ $index }}.cpf"
                                    class="w-full h-12 px-4 text-sm border border-gray-300 rounded-xl
                                    focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                                    placeholder-gray-400 transition"
                                    placeholder="CPF"
                                />
                                @error('guests.' . $index . '.cpf')
                                    <span class="text-red-500 text-xs validation-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <input
                                    type="text"
                                    x-data
                                    x-init="flatpickr($el, {
                                        dateFormat: 'Y-m-d',
                                        altInput: true,
                                        altFormat: 'd/m/Y',
                                        locale: FlatpickrPortuguese,
                                        maxDate: 'today',
                                        onChange: (selectedDates, dateStr) => {
                                            $wire.set('guests.{{ $index }}.birthdate', dateStr)
                                        }
                                    })"
                                    placeholder="Data de nascimento"
                                    class="w-full h-12 px-4 text-sm border border-gray-300 rounded-xl
                                    focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                                    placeholder-gray-400 transition"
                                >
                                @error('guests.' . $index . '.birthdate')
                                    <span class="text-red-500 text-xs validation-error">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>

                    </div>

                    @endforeach

                </div>

            </div>

            @endif


            {{-- BOTÃO --}}
            <div class="bg-white rounded-2xl shadow-sm border p-6">

                @if(!$paymentStatus)

                    @if(!$showPayment)
                        <button
                            wire:click="preparePayment"
                            wire:loading.attr="disabled"
                            type="button"
                            class="w-full py-4 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 transition"
                        >
                            Continuar para pagamento
                        </button>
                    @else
                        {{-- ✅ Brick só aparece após clicar no botão --}}
                        <div id="mp-payment-brick" x-data x-init="initMPBrick()"></div>
                    @endif

                @endif

                {{-- PIX QR Code --}}
                @if($pixQrCodeBase64)
                    <div class="text-center space-y-4">
                        <p class="font-semibold text-gray-800">Escaneie o QR Code para pagar</p>
                        <img src="data:image/png;base64,{{ $pixQrCodeBase64 }}" class="mx-auto w-48 h-48">
                        <p class="text-xs text-gray-500 break-all">{{ $pixQrCode }}</p>
                        <button
                            onclick="navigator.clipboard.writeText('{{ $pixQrCode }}')"
                            class="px-4 py-2 bg-green-600 text-white rounded-xl text-sm"
                        >
                            Copiar código PIX
                        </button>
                    </div>
                @endif

            </div>

        </div>



        {{-- RESUMO DA RESERVA --}}
        <div class="space-y-6">

            {{-- IMÓVEL --}}
            <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">

                <img
                    src="{{ $reservation->property->cover() ?? 'https://placehold.co/600x400' }}"
                    class="w-full h-48 object-cover"
                >

                <div class="p-5">

                    <h3 class="font-semibold text-lg text-gray-900">
                        {{ $reservation->property->title }}
                    </h3>

                    <p class="text-sm text-gray-500">
                        {{ $reservation->property->city }},
                        {{ $reservation->property->state }}
                    </p>

                    <p class="text-sm text-gray-600 mt-3">
                        <p class="text-sm font-semibold text-gray-900 mb-1.5">Política de cancelamento</p>
                        {!! nl2br(e($reservation->property->politica_cancelamento)) !!}
                    </p>

                </div>

            </div>


            {{-- RESUMO --}}
            <div class="bg-white rounded-2xl shadow-sm border p-6 sticky top-6">

                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    Resumo da reserva
                </h3>

                <div class="space-y-3 text-sm text-gray-600">

                    <div class="flex justify-between">
                        <span>Check-in</span>
                        <span class="font-medium text-gray-800">
                            {{ \Carbon\Carbon::parse($reservation->check_in)->format('d/m/Y') }}
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span>Check-out</span>
                        <span class="font-medium text-gray-800">
                            {{ \Carbon\Carbon::parse($reservation->check_out)->format('d/m/Y') }}
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span class="font-medium text-gray-800">
                            {{ $reservation->nights }} noite(s) e {{ $reservation->guests }} hóspede(s)
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span>{{ $reservation->nights }} noite(s)</span>
                        <span class="font-medium text-gray-800">
                            R$ {{ number_format($reservation->daily_total * $reservation->nights, 2, ',', '.') }}
                        </span>
                    </div>
                    @php
                        $hospede_extra = max(0, $reservation->guests - $reservation->property->aditional_person);
                    @endphp
                    <div class="flex justify-between">
                        <span>{{ ($hospede_extra) }} Hóspedes Extra(s)</span>
                        <span class="font-medium text-gray-800">
                            R$ {{ number_format($hospede_extra *($reservation->property->value_aditional * $reservation->nights), 2, ',', '.') }}
                        </span>
                    </div>                    

                    <div class="flex justify-between">
                        <span>Taxa de Limpeza</span>
                        <span class="font-medium text-gray-800">
                            R$ {{ number_format($reservation->cleaning_fee, 2, ',', '.') }}
                        </span>
                    </div>

                    <div class="border-t pt-3 flex justify-between text-base font-semibold text-gray-900">
                        <span>Valor Total</span>
                        <span>
                            R$ {{ number_format($reservation->total_value, 2, ',', '.') }}
                        </span>
                    </div>

                </div>

            </div>

        </div>

    </div>
</div>
</div>

@push('scripts')
    <script src="https://sdk.mercadopago.com/js/v2"></script>
<script>
    document.addEventListener('livewire:initialized', () => {

        Livewire.hook('morph.updated', ({ el }) => {
            const error = document.querySelector('.validation-error');
            if (error) {
                error.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }

            // Inicializa o brick quando showPayment virar true
            if (document.getElementById('mp-payment-brick') && !window.mpBrickLoaded) {
                window.mpBrickLoaded = true;
                initMPBrick();
            }
        });

    });

    async function initMPBrick() {
        const mp = new MercadoPago('{{ config('services.mercadopago.key') }}', { locale: 'pt-BR' });

        await mp.bricks().create('payment', 'mp-payment-brick', {
            initialization: {
                amount: {{ $reservation->total_value }},
            },
            customization: {
                paymentMethods: {
                    creditCard:      'all',
                    debitCard:       'all',
                    bankTransfer:    ['pix'],
                    maxInstallments: 12,
                    // ❌ remova o ticket completamente — não inclua a chave
                },
            },
            callbacks: {
                onReady: () => {
                    // Brick carregado
                    console.log('MP Brick pronto');
                },
                onSubmit: async ({ formData }) => {
                    await $wire.processPayment(formData);
                },
                onError: (error) => {
                    console.error('Brick error:', error);
                }
            }
        });
    }
    
</script>

    <script>
        document.addEventListener('livewire:initialized', () => {

            Livewire.hook('morph.updated', ({ el }) => {
                const error = document.querySelector('.validation-error');
                if (error) {
                    error.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            });

        });
    </script>
@endpush
