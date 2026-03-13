@extends('web.layouts.app')

@section('content')

<div class="max-w-3xl mx-auto px-6 py-16 text-center">

    <div class="bg-white rounded-2xl shadow-sm border p-10">

        <div class="text-red-500 text-5xl mb-4">
            ✕
        </div>

        <h1 class="text-2xl font-bold text-gray-900 mb-3">
            Pagamento cancelado
        </h1>

        <p class="text-gray-600 mb-6">
            O pagamento não foi concluído. Você pode tentar novamente.
        </p>

        <div class="mt-8">

            <a href="{{ route('web.reservation.form', $reservation->review_token) }}"
               class="inline-block px-6 py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition">
                Tentar novamente
            </a>

        </div>

    </div>

</div>

@endsection