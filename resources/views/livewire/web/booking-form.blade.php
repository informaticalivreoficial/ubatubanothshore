@php
    $individualPrice = $property->priceForGuests(1);
    $couplePrice = $property->priceForGuests(2);
    $maxGuests = $property->capacity + $property->aditional_person;
@endphp

<div class="space-y-5">
    <div>
        <p class="text-xs font-medium uppercase tracking-wide text-gray-500">Diárias a partir de</p>
        <div class="mt-1 flex items-baseline gap-1">
            <span class="text-2xl font-bold text-gray-900">
                R$ {{ number_format($individualPrice, 0, ',', '.') }}
            </span>
            <span class="text-sm text-gray-500">/ noite</span>
        </div>

        @if($couplePrice > $individualPrice)
            <p class="mt-1 text-xs text-gray-500">
                2 hóspedes: R$ {{ number_format($couplePrice, 0, ',', '.') }} / noite
                <span class="text-gray-400">(total para os dois)</span>
            </p>
        @endif

        <p class="mt-2 text-xs font-semibold text-blue-700">
            Mínimo de {{ $property->min_nights }} diária(s)
        </p>
    </div>

    @if($bookingClosed)
        <div class="rounded-lg border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800">
            <p class="font-semibold">Reservas encerradas</p>
            <p class="mt-1">Este imóvel não está mais disponível para novas reservas.</p>
        </div>

        <a
            href="{{ route('web.properties') }}"
            class="flex w-full items-center justify-center rounded-lg border border-gray-300 bg-white py-3 text-sm font-bold text-gray-700 transition-colors hover:bg-gray-50"
        >
            Ver outros imóveis
        </a>
    @else
        <div
            wire:ignore
            x-data="dateRangePicker()"
            x-init="init()"
            data-disabled='@json($disabledDates)'
            data-method="calculate"
            data-max-date="{{ $maxBookingDate }}"
            data-months="1"
            class="space-y-2"
        >
            <label class="block text-sm font-medium text-gray-700">
                Selecione as datas
            </label>

            <input
                type="text"
                x-ref="picker"
                class="w-full cursor-pointer rounded-lg border border-gray-300 px-3 py-3 text-sm text-gray-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Check-in e check-out"
                autocomplete="off"
            >
        </div>

        @if($dateError)
            <div class="rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700" role="alert">
                {{ $dateError }}
            </div>
        @endif

        <div class="flex items-center justify-between rounded-lg border border-gray-300 px-4 py-3">
            <div>
                <p class="text-sm font-medium text-gray-800">Hóspedes</p>
                <p class="text-xs text-gray-500">Máximo de {{ $maxGuests }}</p>
            </div>

            <div class="flex items-center gap-3">
                <button
                    type="button"
                    wire:click="decreaseGuests"
                    @disabled($guests <= 1)
                    class="flex h-8 w-8 items-center justify-center rounded-full border border-gray-300 text-gray-700 transition-colors hover:bg-gray-100 disabled:cursor-not-allowed disabled:opacity-40"
                    aria-label="Diminuir hóspedes"
                >
                    -
                </button>

                <span class="w-5 text-center text-sm font-semibold text-gray-900">{{ $guests }}</span>

                <button
                    type="button"
                    wire:click="increaseGuests"
                    @disabled($guests >= $maxGuests)
                    class="flex h-8 w-8 items-center justify-center rounded-full border border-gray-300 text-gray-700 transition-colors hover:bg-gray-100 disabled:cursor-not-allowed disabled:opacity-40"
                    aria-label="Aumentar hóspedes"
                >
                    +
                </button>
            </div>
        </div>

        @if($nights > 0)
            <div class="space-y-2 border-t border-gray-200 pt-4 text-sm">
                <div class="flex justify-between gap-4">
                    <span class="text-gray-600">
                        @if ($seasonApplied)
                            {{ $seasonNights }} noite(s) de temporada
                            @if ($baseNights > 0)
                                + {{ $baseNights }} noite(s) na tarifa normal
                            @endif
                        @else
                            {{ $nights }} noite(s) x
                            R$ {{ number_format($property->priceForGuests($guests), 2, ',', '.') }}
                            <span class="text-xs text-gray-400">
                                ({{ $guests >= 2 ? 'total para os 2 primeiros hóspedes' : '1 hóspede' }})
                            </span>
                        @endif
                    </span>
                    <span class="font-medium text-gray-800">
                        R$ {{ number_format($dailyTotal, 2, ',', '.') }}
                    </span>
                </div>

                @if($extraGuests > 0)
                    <div class="flex justify-between gap-4 text-orange-600">
                        <span>
                            {{ $extraGuests }} hóspede(s) extra(s)
                            x R$ {{ number_format($property->value_aditional, 2, ',', '.') }}
                            x {{ $nights }} noite(s)
                        </span>
                        <span>
                            R$ {{ number_format($extraTotal, 2, ',', '.') }}
                        </span>
                    </div>
                @endif

                @if($property->cleaning_fee)
                    <div class="flex justify-between gap-4">
                        <span class="text-gray-600">Taxa de limpeza</span>
                        <span class="font-medium text-gray-800">
                            R$ {{ number_format($property->cleaning_fee, 2, ',', '.') }}
                        </span>
                    </div>
                @endif

                @if($iptuFee > 0)
                    <div class="flex justify-between gap-4">
                        <span class="text-gray-600">Taxa de IPTU</span>
                        <span class="font-medium text-gray-800">
                            R$ {{ number_format($iptuFee, 2, ',', '.') }}
                        </span>
                    </div>
                @endif

                <div class="flex justify-between gap-4 border-t border-gray-200 pt-3 text-base font-bold text-gray-900">
                    <span>Total</span>
                    <span>R$ {{ number_format($total, 2, ',', '.') }}</span>
                </div>
            </div>
        @endif

        <button
            wire:click="goToCheckout"
            wire:loading.attr="disabled"
            class="flex w-full items-center justify-center rounded-lg bg-blue-600 py-3.5 text-sm font-bold text-white transition-colors hover:bg-blue-700 disabled:cursor-wait disabled:opacity-70"
        >
            <span wire:loading.remove>Continuar para reserva</span>
            <span wire:loading>Verificando...</span>
        </button>

        <p class="text-center text-xs text-gray-500">
            Você só conclui a reserva na próxima etapa.
        </p>
    @endif
</div>
