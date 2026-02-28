@extends('web.layouts.app')

@section('content')

<div class="max-w-7xl mx-auto px-6 lg:px-8 py-8 space-y-8">

    {{-- GALERIA --}}
    <div class="rounded-xl overflow-hidden">
        @include('web.components.photo-gallery', [
            'images' => $property->images->pluck('url')->toArray()
        ])
    </div>

    {{-- TITULO E LOCALIZAÇÃO --}}
    <div class="space-y-2">
        <h1 class="text-3xl font-bold text-gray-900">{{ $property->title }}</h1>
        <p class="text-sm text-gray-600">
            {{ $property->city }}, {{ $property->state }}
        </p>
    </div>

    {{-- PRINCIPAL / SIDEBAR --}}
    <div class="grid lg:grid-cols-3 gap-8">

        {{-- DESCRIÇÃO PRINCIPAL --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- DESCRIÇÃO --}}
            <div class="bg-white p-6 rounded-xl shadow-sm">
                {!! nl2br(e($property->description)) !!}
            </div>

            {{-- DETALHES DO IMÓVEL --}}
            <div class="grid grid-cols-2 gap-4 text-sm text-gray-700">
                <div><strong>Dormitórios:</strong> {{ $property->dormitories }}</div>
                <div><strong>Banheiros:</strong> {{ $property->bathrooms }}</div>
                <div><strong>Garagem:</strong> {{ $property->garage }}</div>
                <div><strong>Area Útil:</strong> {{ $property->useful_area }} m²</div>
            </div>

        </div>

        {{-- SIDEBAR RESERVA --}}
        <aside class="lg:sticky lg:top-24">
            <div class="bg-white p-6 rounded-2xl shadow-lg space-y-4">

                <div class="text-2xl font-bold text-gray-900">
                    R$ {{ number_format($property->rental_value, 2, ',', '.') }}
                    <span class="text-sm font-normal text-gray-600">/ noite</span>
                </div>

                {{-- DATAS --}}
                <input
                    type="date"
                    x-model="check_in"
                    wire:model.live="check_in"
                    class="w-full border rounded-lg p-2"
                    placeholder="Check-in">

                <input
                    type="date"
                    x-model="check_out"
                    wire:model.live="check_out"
                    class="w-full border rounded-lg p-2"
                    placeholder="Check-out">

                @if($available === false)
                    <div class="text-sm text-red-600">Datas indisponíveis</div>
                @elsif($available)
                    <div class="text-sm text-gray-800">
                        <div>Noites: {{ $nights }}</div>
                        <div>Subtotal: R$ {{ number_format($daily_total, 2, ',', '.') }}</div>
                        <div>Limpeza: R$ {{ number_format($cleaning_fee, 2, ',', '.') }}</div>
                        <div class="font-bold text-lg mt-2">Total: R$ {{ number_format($total_value, 2, ',', '.') }}</div>
                    </div>
                @endif

                <button
                    wire:click="submitReservation"
                    class="w-full bg-blue-600 text-white text-center py-3 rounded-xl font-semibold hover:bg-blue-700 transition">
                    Reservar agora
                </button>

            </div>
        </aside>

    </div>

</div>

@endsection