<div>
    <div class="space-y-4">

    <div>
        <label>Check-in</label>
        <input type="date" wire:model.live="checkin">
    </div>

    <div>
        <label>Check-out</label>
        <input type="date" wire:model.live="checkout">
    </div>

    @if($checkin && $checkout)

        @if($available === false)
            <div class="text-red-600">
                Datas indisponíveis
            </div>
        @endif

        @if($available)
            <div class="text-green-600">
                Disponível
            </div>

            <div class="text-xl font-bold">
                Total: R$ {{ number_format($total, 2, ',', '.') }}
            </div>
        @endif

    @endif

</div>
</div>
