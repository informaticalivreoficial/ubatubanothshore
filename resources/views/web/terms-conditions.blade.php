@extends('web.layouts.app')

@section('content')
    @include('web.components.legal-page', [
        'title' => 'Termos e Condições',
        'description' => 'Conheça as regras para uso da plataforma, realização de reservas e contratação de imóveis.',
        'badge' => 'Uso da plataforma',
        'icon' => 'document',
        'features' => ['Regras claras', 'Reservas seguras', 'Transparência'],
        'content' => $legal['html'],
        'sections' => $legal['sections'],
        'current' => 'terms',
    ])
@endsection
