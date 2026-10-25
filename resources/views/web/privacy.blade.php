@extends('web.layouts.app')

@section('content')
    @include('web.components.legal-page', [
        'title' => 'Política de Privacidade',
        'description' => 'Entenda como a Ubatuba North Shore coleta, utiliza, armazena e protege os seus dados pessoais.',
        'badge' => 'Privacidade e LGPD',
        'icon' => 'shield',
        'features' => ['Compromisso com a LGPD', 'Transparência', 'Segurança dos dados'],
        'content' => $legal['html'],
        'sections' => $legal['sections'],
        'current' => 'privacy',
    ])
@endsection
