@extends("web.$configuracoes->template.master.master")

@section('content')
    <div class="sub-banner overview-bgi" style="background: rgba(0, 0, 0, 0.04) url({{$configuracoes->getheadersite()}}) top left repeat;">
        <div class="overlay">
            <div class="container">
                <div class="breadcrumb-area">
                    <h1 style="font-size: 36px;">Atendimento</h1>
                    <ul class="breadcrumbs">
                        <li><a href="{{route('web.home')}}">Início</a></li>
                        <li class="active">Atendimento</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="contact-1 content-area-7">
        <div class="container">
            <div class="row">
                <div class="col-lg-7 col-md-7 col-sm-6 col-xs-12">
                    <div class="contact-form">
                        <livewire:web.contact-form  />  
                    </div>
                </div>
                <div class="col-lg-4 col-lg-offset-1 col-md-4 col-md-offset-1 col-sm-6 col-xs-12">
                    <div class="contact-details">
                        <div class="main-title-2">
                            <h3>Outros Canais</h3>
                        </div>
                        @if ($configuracoes->display_address)
                            <div class="media">
                                <div class="media-left">
                                <i class="fa fa-map-marker"></i>
                                </div>
                                <div class="media-body">
                                <h4>Escritório</h4>
                                <p>
                                    @if ($configuracoes->street) {{ $configuracoes->street }} @endif
                                    @if ($configuracoes->number) , {{ $configuracoes->number }} @endif
                                    @if ($configuracoes->neighborhood) , {{ $configuracoes->neighborhood }} @endif
                                    @if ($configuracoes->city) - {{ $configuracoes->city }} @endif
                                    @if ($configuracoes->state) / {{ $configuracoes->state }} @endif
                                </p>
                                </div>
                            </div>
                        @endif
                        @if ($configuracoes->phone || $configuracoes->cell_phone)
                            <div class="media">
                                <div class="media-left">
                                    <i class="fa fa-phone"></i>
                                </div>
                                <div class="media-body">
                                    <h4>Telefone</h4>
                                    <p><a href="tel:{{$configuracoes->phone}}">{{$configuracoes->phone}}</a></p>                               
                                    <p><a href="tel:{{$configuracoes->cell_phone}}">{{$configuracoes->cell_phone}}</a></p>
                                </div>
                            </div>                            
                        @endif
                        
                        @if ($configuracoes->whatsapp)
                            <div class="media">
                                <div class="media-left">
                                    <i class="fa fa-whatsapp"></i>
                                </div>
                                <div class="media-body">
                                    <h4>WhatsApp</h4>
                                    <p><a target="_blank" href="{{ \App\Helpers\WhatsApp::getNumZap($configuracoes->whatsapp, 'Atendimento '.$configuracoes->app_name) }}">{{ $configuracoes->whatsapp }}</a></p>    
                                </div>
                            </div>                            
                        @endif
                        
                        @if ($configuracoes->email)
                            <div class="media mb-0">
                                <div class="media-left">
                                    <i class="fa fa-envelope"></i>
                                </div>
                                <div class="media-body">
                                    <h4>E-mail</h4>
                                    <p><a href="mailto:{{$configuracoes->email}}">{{$configuracoes->email}}</a></p>
                                    @if ($configuracoes->additional_email)
                                        <p><a href="tel:{{$configuracoes->additional_email}}">{{$configuracoes->additional_email}}</a></p>
                                    @endif 
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection