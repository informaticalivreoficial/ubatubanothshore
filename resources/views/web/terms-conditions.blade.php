@extends('web.layouts.app')

@section('content')
    <div class="max-w-8xl mx-auto px-6 py-4">

        <div class="flex flex-col lg:flex-row gap-8">
            <div class="w-full">
                <h2 class="text-2xl font-semibold mb-4 pt-6">Termos e Condições</h2>
                {!!$configuracoes->terms_condicions!!}
            </div>
        </div>

    </div>
@endsection