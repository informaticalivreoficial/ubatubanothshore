<div>
    <div class="border rounded-lg p-4 space-y-4">

        {{-- Datas --}}
        <div
            wire:ignore
            x-data="dateRangePicker()"
            x-init="init()"
            data-disabled='@json($disabledDates)'
            class="space-y-2"
        >

            <label class="block text-sm font-medium">
                Selecione as Datas
            </label>

            <input
                type="text"
                x-ref="picker"
                class="w-full border rounded p-2"
                placeholder="Selecione check-in e check-out"
            >
        </div>

        @if($dateError)
            <div class="bg-red-50 text-red-600 text-sm p-2 rounded">
                {{ $dateError }}
            </div>
        @endif

        {{-- Hóspedes --}}
        <div class="flex justify-between items-center">
            <span>{{ $guests }} hóspede(s)</span>

            <div class="flex gap-2">
                <button type="button"
                    wire:click="decreaseGuests"
                    class="border w-8 h-8 rounded">-</button>

                <button type="button"
                    wire:click="increaseGuests"
                    class="border w-8 h-8 rounded">+</button>
            </div>
        </div>

        {{-- Resumo --}}
        @if($nights > 0)

            <div class="border-t pt-4 space-y-2 text-sm">

                {{-- Diária --}}
                <div class="flex justify-between">                    
                    <span>
                        {{ $nights }} noite(s) X
                        @if ($seasonApplied)
                            <span class="text-xs text-gray-400">(temporadas aplicadas)</span>
                        @else    
                            R$ {{ number_format($property->rental_value, 2, ',', '.') }}
                        @endif                        
                    </span>
                    <span>
                        R$ {{ number_format($dailyTotal, 2, ',', '.') }}
                    </span>
                </div>

                {{-- Adicional por pessoa --}}
                @if($extraGuests > 0)
                    <div class="flex justify-between text-orange-600">
                        <span>
                            R$ {{ number_format($property->value_aditional, 2, ',', '.') }}
                            x {{ $extraGuests }} pessoa(s) extra(s)
                            x {{ $nights }} noite(s)
                        </span>
                        <span>
                            R$ {{ number_format($extraTotal, 2, ',', '.') }}
                        </span>
                    </div>
                @endif

                {{-- Limpeza --}}
                @if($property->cleaning_fee)
                    <div class="flex justify-between">
                        <span>Taxa de limpeza</span>
                        <span>
                            R$ {{ number_format($property->cleaning_fee, 2, ',', '.') }}
                        </span>
                    </div>
                @endif

                <div class="border-t pt-3 flex justify-between font-bold text-lg">
                    <span>Total</span>
                    <span>
                        R$ {{ number_format($total, 2, ',', '.') }}
                    </span>
                </div>

            </div>

        @endif

    </div>

    <button
        wire:click="goToCheckout"
        class="w-full bg-white font-bold border border-gray-300 text-gray-700 py-3 rounded-lg hover:bg-gray-100 transition-colors mt-4"
    >
        Verificar Disponibilidade
    </button>
</div>

@push('styles')
    <style>
        
    </style>
@endpush

@push('scripts')
    <script>
        
        function dateRangePicker() {
            return {
                init() {

                    let disabledDates = JSON.parse(this.$el.dataset.disabled)
                    .map(date => {
                        const [y, m, d] = date.split('-').map(Number)
                        return new Date(y, m - 1, d) // ✅ horário local, sem UTC
                    })                    

                    flatpickr(this.$refs.picker, {
                        mode: "range",
                        dateFormat: "Y-m-d",
                        altInput: true,
                        altFormat: "d/m/Y",
                        showMonths: 2,
                        minDate: new Date(),
                        locale: FlatpickrPortuguese, // ✅
                        rangeSeparator: " até ",
                        disable: disabledDates,
                        utc: false,
                        onReady: function(selectedDates, dateStr, instance) {
                            instance.set('locale', {
                                ...instance.l10n,
                                firstDayOfWeek: 1
                            });
                        },

                        

                        onChange: (selectedDates) => {
                            if (selectedDates.length === 2) {

                                let formatToISO = (date) => {
                                    const y = date.getFullYear()
                                    const m = String(date.getMonth() + 1).padStart(2, '0')
                                    const d = String(date.getDate()).padStart(2, '0')
                                    return `${y}-${m}-${d}`
                                }

                                @this.set('check_in', formatToISO(selectedDates[0]));
                                @this.set('check_out', formatToISO(selectedDates[1]));
                                @this.call('calculate');
                            }
                            
                        }
                    });

                    //console
                }
            }
        }
    </script>
@endpush