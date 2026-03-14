@extends('web.layouts.app')

@section('content')

<div class="max-w-3xl mx-auto px-6 py-16 text-center">

    <div class="bg-white rounded-2xl shadow-sm border p-10">

        <div class="text-yellow-500 text-5xl mb-4">
            !
        </div>

        <h1 class="text-2xl font-bold text-gray-900 mb-3">
            Pagamento Pendente de Confirmação!
        </h1>

        <p class="text-gray-600 mb-6">
            Seu pagamento ainda está pendente de confirmação.
        </p>

        <div class="text-sm text-gray-600 space-y-2">

            <p>
                <strong>Imóvel:</strong>
                {{ $reservation->property->title }}
            </p>

            <p>
                <strong>Check-in:</strong>
                {{ \Carbon\Carbon::parse($reservation->check_in)->format('d/m/Y') }}
            </p>

            <p>
                <strong>Check-out:</strong>
                {{ \Carbon\Carbon::parse($reservation->check_out)->format('d/m/Y') }}
            </p>

        </div>

        <div class="mt-8">

            <a href="{{ route('web.reservation.form', $reservation->review_token) }}"
               class="inline-block px-6 py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition">
                Finalizar Reserva
            </a>

        </div>

    </div>

</div>

@endsection