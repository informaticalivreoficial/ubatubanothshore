@extends("web.$configuracoes->template.master.master")

@section('content')
    <div class="sub-banner overview-bgi" style="background: rgba(0, 0, 0, 0.04) url({{$configuracoes->getheadersite()}}) top left repeat;">
        <div class="overlay">
            <div class="container">
                <div class="breadcrumb-area">
                    <h1>{{$property->title}}</h1>
                    <ul class="breadcrumbs">
                        <li><a href="{{route('web.home')}}">Início</a></li>
                        <li class="active">Imóveis -  {{$property->title}}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="properties-details-page content-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                    <div class="heading-properties clearfix sidebar-widget">
                        <div class="pull-left">
                            <h3>{{$property->title}}</h3>
                            <p>
                                @if ($property->display_address)                                
                                    <i class="fa fa-map-marker"></i>{{$property->neighborhood}}, {{$property->city}}/{{$property->state}}
                                @endif
                            </p>                            
                        </div>
                        <div class="pull-right">
                            <h3>
                                <span>
                                    @if ($property->display_values)
                                        @php
                                            $venda = ($property->sale_value && !empty($property->sale_value))
                                                ? 'R$' . number_format($property->sale_value, 0, ',', '.')
                                                : null;

                                            $aluguel = ($property->rental_value && !empty($property->rental_value))
                                                ? 'R$' . number_format($property->rental_value, 0, ',', '.') . '/'. $property->getLocationPeriod()
                                                : null;
                                        @endphp
                                        @if($venda && $aluguel)
                                            Venda: {{ $venda }} - Locação: {{ $aluguel }}
                                        @elseif($venda)
                                            Venda: {{ $venda }}
                                        @elseif($aluguel)
                                            Locação: {{ $aluguel }}
                                        @else
                                            Sob Consulta
                                        @endif
                                    @endif
                                </span>
                            </h3>
                            {{-- $objetivo = ($tipo == '1' ? 'Aluga' : 'Vende');--}}
                            @if ($property->reference)
                                <h5>
                                    Referência: {{ $property->reference }}
                                </h5>                                
                            @endif                            
                        </div>
                    </div>
                    
                    <div class="sidebar-widget mb-10">
                        <!-- Properties description start -->
                        <div class="properties-description mb-10">
                            <div class="property-img">
                                <img class="imgimovel" src="{{$property->cover()}}" alt="{{$property->reference}}">
                                <div class="property-overlay">
                                    @if($property->images()->get()->count())
                                        <div class="property-magnify-gallery"> 
                                            <a style="font-size:22px;line-height: 46px;width: 56px; height: 56px;" href="{{$property->cover()}}" class="overlay-link"><i class="fa fa-expand"></i></a>
                                            @foreach($property->images()->get() as $image)                                      
                                                <a href="{{ $image->url_image }}" class="hidden"></a>                                      
                                            @endforeach
                                        </div>
                                    @endif
                                    @auth
                                        @if(auth()->user()->canEditProperties())
                                            <a 
                                                href="{{ route('property.edit', $property->id) }}"
                                                class="absolute top-2 right-2 z-50 bg-black/70 text-white hover:text-white px-4 py-3 rounded text-sm hover:bg-black"
                                                title="Editar imóvel"
                                            >
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                        @endif
                                    @endauth
                                </div>
                                @if ($property->caption_img_cover)
                                    <p>{{$property->caption_img_cover}}</p>
                                @endif
                            </div>

                            <div class="main-title-2">
                                <h1 style="margin-top:10px;"><span>Informações</span></h1>
                                <br />
                                <!-- Social list -->
                                <div id="shareIcons"></div>                        
                            </div>                        
                        </div>
                        <!-- Properties description end -->
                        
                        <div class="panel-box properties-panel-box Property-description">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#tab1default" data-toggle="tab" aria-expanded="true">Descrição</a></li>
                                <li class=""><a href="#tab2default" data-toggle="tab" aria-expanded="false">Condições do Imóvel</a></li>
                                <li class=""><a href="#tab3default" data-toggle="tab" aria-expanded="false">Acessórios</a></li>                                                    
                            </ul>
                            <div class="panel with-nav-tabs panel-default">
                                <div class="panel-body">
                                    <div class="tab-content">
                                        <div class="tab-pane fade active in" id="tab1default">                                        
                                            {!!$property->description!!}
                                        </div>
                                        <div class="tab-pane fade features" id="tab2default">
                                            <div class="properties-condition">
                                                <div class="row">
                                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                                        <ul class="condition">
                                                            @if ($property->dormitories)
                                                                <li>
                                                                    <i class="fa fa-check-square"></i>
                                                                    <span>Dormitórios: {{$property->dormitories}}</span>
                                                                </li>                                                                
                                                            @endif
                                                            @if ($property->bathrooms)
                                                                <li>
                                                                    <i class="fa fa-check-square"></i>
                                                                    <span>Banheiros: {{$property->bathrooms}}</span>
                                                                </li>                                                                
                                                            @endif
                                                            @if ($property->iptu && $property->display_values)
                                                                <li>
                                                                    <i class="fa fa-check-square"></i>
                                                                    <span>Valor IPTU: R${{number_format($property->iptu, 0, ',', '.')}}</span>
                                                                </li>                                                                
                                                            @endif          
                                                        </ul>
                                                    </div>
                                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                                        <ul class="condition">
                                                            @if ($property->total_area)
                                                                <li>
                                                                    <i class="fa fa-check-square"></i>
                                                                    <span>Área Total: {{$property->total_area}} {{ $property->measures }}</span>
                                                                </li>                                                                
                                                            @endif
                                                            @if ($property->useful_area)
                                                                <li>
                                                                    <i class="fa fa-check-square"></i>
                                                                    <span>Área Construída: {{$property->useful_area}} {{ $property->measures }}</span>
                                                                </li>                                                                
                                                            @endif
                                                            @if ($property->condominium && $property->display_values)
                                                                <li>
                                                                    <i class="fa fa-check-square"></i>
                                                                    <span>Condomínio: R${{number_format($property->condominium, 0, ',', '.')}}</span>
                                                                </li>                                                                
                                                            @endif                                                                         
                                                        </ul>
                                                    </div>
                                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                                        <ul class="condition">
                                                            @if ($property->garage || $property->covered_garage)
                                                                <li>
                                                                    <i class="fa fa-check-square"></i>
                                                                    <span>Garagem: {{($property->garage + $property->covered_garage)}}</span>
                                                                </li>                                                                
                                                            @endif                                                                                             
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade technical" id="tab3default">
                                            <div class="properties-amenities">                                            
                                                <div class="row"> 
                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                        <ul class="list-2">                                                        
                                                            @if($property->ar_condicionado == true)
                                                                <li style="margin-bottom: 0px !important;">Ar Condicionado></li>
                                                            @endif

                                                            @if($property->aquecedor_solar == true)
                                                                <li style="margin-bottom: 0px !important;">Aquecedor Solar</li>
                                                            @endif

                                                            @if($property->armarionautico == true)
                                                                <li style="margin-bottom: 0px !important;">Armário Náutico</li>
                                                            @endif

                                                            @if($property->balcaoamericano == true)
                                                                <li style="margin-bottom: 0px !important;">Balcão Americano</li>
                                                            @endif

                                                            @if($property->banheira == true)
                                                                <li style="margin-bottom: 0px !important;">Banheira</li>
                                                            @endif 
                                                            
                                                            @if($property->elevador == true)
                                                                <li style="margin-bottom: 0px !important;">Elevador</li>
                                                            @endif

                                                            @if($property->escritorio == true)
                                                                <li style="margin-bottom: 0px !important;">Escritório</li>
                                                            @endif

                                                            @if($property->espaco_fitness == true)
                                                                <li style="margin-bottom: 0px !important;">Espaço Fitness</li>
                                                            @endif

                                                            @if($property->estacionamento == true)
                                                                <li style="margin-bottom: 0px !important;">Estacionamento</li>
                                                            @endif

                                                            @if($property->fornodepizza == true)
                                                                <li style="margin-bottom: 0px !important;">Forno de Pizza</li>
                                                            @endif

                                                            @if($property->quadrapoliesportiva == true)
                                                                <li style="margin-bottom: 0px !important;">Quadra poliesportiva</li>
                                                            @endif

                                                            @if($property->quintal == true)
                                                                <li style="margin-bottom: 0px !important;">Quintal</li>
                                                            @endif

                                                            @if($property->sauna == true)
                                                                <li style="margin-bottom: 0px !important;">Sauna</li>
                                                            @endif

                                                            @if($property->saladetv == true)
                                                                <li style="margin-bottom: 0px !important;">Sala de TV</li>
                                                            @endif
                                                        </ul>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                        <ul class="list-2">                                                        
                                                            @if($property->banheirosocial == true)
                                                                <li>Banheiro Social</li>
                                                            @endif

                                                            @if($property->bar == true)
                                                                <li>Bar Social</li>
                                                            @endif

                                                            @if($property->biblioteca == true)
                                                                <li>Biblioteca</li>
                                                            @endif

                                                            @if($property->brinquedoteca == true)
                                                                <li>Brinquedoteca</li>
                                                            @endif

                                                            @if($property->condominiofechado == true)
                                                                <li>Condomínio fechado</li>
                                                            @endif 
                                                            
                                                            @if($property->geradoreletrico == true)
                                                                <li>Gerador elétrico</li>
                                                            @endif

                                                            @if($property->interfone == true)
                                                                <li>Interfone</li>
                                                            @endif

                                                            @if($property->jardim == true)
                                                                <li>Jardim</li>
                                                            @endif

                                                            @if($property->lareira == true)
                                                                <li>Lareira</li>
                                                            @endif

                                                            @if($property->lavabo == true)
                                                                <li>Lavabo</li>
                                                            @endif

                                                            @if($property->salaodefestas == true)
                                                                <li>Salão de Festas</li>
                                                            @endif

                                                            @if($property->salaodejogos == true)
                                                                <li>Salão de Jogos</li>
                                                            @endif

                                                            @if($property->zeladoria == true)
                                                                <li>Serviço de Zeladoria</li>
                                                            @endif

                                                            @if($property->sistemadealarme == true)
                                                                <li>Sistema de alarme</li>
                                                            @endif
                                                        </ul>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                        <ul class="list-2">                                                        
                                                            @if($property->cozinha_americana == true)
                                                                <li>Cozinha Americana</li>
                                                            @endif

                                                            @if($property->cozinha_planejada == true)
                                                                <li>Cozinha Planejada</li>
                                                            @endif

                                                            @if($property->churrasqueira == true)
                                                                <li>Churrasqueira</li>
                                                            @endif

                                                            @if($property->dispensa == true)
                                                                <li>Despensa</li>
                                                            @endif

                                                            @if($property->edicula == true)
                                                                <li>Edicula</li>
                                                            @endif    
                                                            
                                                            @if($property->lavanderia == true)
                                                                <li>Lavanderia</li>
                                                            @endif

                                                            @if($property->mobiliado == true)
                                                                <li>Mobiliado</li>
                                                            @endif

                                                            @if($property->pertodeescolas == true)
                                                                <li>Perto de Escolas</li>
                                                            @endif

                                                            @if($property->piscina == true)
                                                                <li>Piscina</li>
                                                            @endif

                                                            @if($property->portaria24hs == true)
                                                                <li>Portaria 24 Horas</li>
                                                            @endif

                                                            @if($property->permiteanimais == true)
                                                                <li>Permite animais</li>
                                                            @endif

                                                            @if($property->varandagourmet == true)
                                                                <li>Varanda Gourmet</li>
                                                            @endif

                                                            @if($property->vista_para_mar == true)
                                                                <li>Vista para o Mar</li>
                                                            @endif

                                                            @if($property->ventilador_teto == true)
                                                                <li>Ventilador de Teto</li>
                                                            @endif
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>                                                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        @if ($property->additional_notes)
                            <hr /><p class="mt-3 mb-3" style="font-size: 12px;width:100%;">{{$property->additional_notes}}</p>
                        @endif

                        @if ($property->google_map)
                            <hr />
                            <div class="location sidebar-widget">
                                <div class="map">
                                    <div class="main-title-2">
                                        <h1><span>Localização</span></h1>
                                    </div>
                                    <div id="map" class="contact-map" style="position: relative; overflow: hidden;">
                                        <div style="height: 100%; width: 100%; position: absolute; top: 0px; left: 0px;">
                                            <iframe class="resp-iframe" src="{{$property->google_map}}" gesture="media"  allow="encrypted-media" allowfullscreen></iframe>
                                        </div>
                                    </div>    
                                </div>    
                            </div>
                        @endif
                        
                        <hr />

                        <div class="contact-1">
                            <div class="contact-form">
                                <div class="main-title-2 p-3">
                                    <h1><span>Consultar este imóvel</span></h1>
                                </div>
                                <livewire:web.contact-property reference="{{$property->reference}}" /> 
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <div class="sidebar right">
                        <div class="advabced-search hidden-lg hidden-md" style="margin-bottom: 30px;">    
                            <a href="">
                                <button class="search-button">Buscar Imóveis</button>
                            </a>    
                        </div>
                        
                        <div class="sidebar-widget hidden-sm hidden-xs">
                            <div class="main-title-2">
                                <h1><span>Busca Avançada</span></h1>
                            </div>
                            <livewire:web.search-properties /> 
                            
                        </div>

                        <!-- Social media start -->
                        <div class="social-media sidebar-widget clearfix">
                            <!-- Main Title 2 -->
                            <div class="main-title-2">
                                <h1><span>Redes Sociais</span></h1>
                            </div>
                            <ul class="social-list">
                                @if ($configuracoes->facebook)
                                    <li>
                                        <a target="_blank" href="{{$configuracoes->facebook}}" class="facebook">
                                            <i class="fa fa-facebook"></i>
                                        </a>
                                    </li>
                                @endif
                                @if ($configuracoes->twitter)
                                    <li>
                                        <a target="_blank" href="{{$configuracoes->twitter}}" class="twitter">
                                            <i class="fa fa-twitter"></i>
                                        </a>
                                    </li>
                                @endif
                                @if ($configuracoes->linkedin)
                                    <li>
                                        <a target="_blank" href="{{$configuracoes->linkedin}}" class="linkedin">
                                            <i class="fa fa-linkedin"></i>
                                        </a>
                                    </li>
                                @endif
                                @if ($configuracoes->instagram)
                                    <li>
                                        <a target="_blank" href="{{$configuracoes->instagram}}" class="instagram">
                                            <i class="fa fa-instagram"></i>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </div>

                        <div class="sidebar-widget helping-box clearfix">
                            <div class="main-title-2">
                                <h1><span>Atendimento</span></h1>
                            </div>    
                            @if ($configuracoes->phone || $configuracoes->cell_phone)
                                <div class="helping-center">
                                    <div class="icon"><i class="fa fa-phone"></i></div>
                                    <h4>Telefone</h4>
                                    @if ($configuracoes->phone)
                                        <p><a href="tel:{{$configuracoes->phone}}">{{$configuracoes->phone}}</a> </p>
                                    @endif
                                    @if ($configuracoes->cell_phone)
                                        <p><a href="tel:{{$configuracoes->cell_phone}}">{{$configuracoes->cell_phone}}</a> </p>
                                    @endif
                                </div>                                
                            @endif 
                            @if ($configuracoes->whatsapp)
                                <div class="helping-center">
                                    <div class="icon"><i class="fa fa-whatsapp"></i></div>
                                    <h4>WhatsApp</h4>
                                    <p><a target="_blank" href="{{ \App\Helpers\WhatsApp::getNumZap($configuracoes->whatsapp, 'Atendimento '.$configuracoes->app_name) }}">{{ $configuracoes->whatsapp }}</a> </p>
                                </div>                                
                            @endif                  
                        </div>
                        <div class="clearfix"></div>                  
                    </div>
                </div>
            </div>
        
            <div class="row">    
                @if ($propertiesrelated && $propertiesrelated->count() > 0)
                    <div class="main-title-2">
                        <h1><span>Veja também</span></h1>
                    </div>

                    <div class="row">
                        @foreach ($propertiesrelated as $item)
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <div class="property-2" style="min-height: 350px !important;">
                                    <div class="property-img">
                                            @if ($item->reference)
                                                <div class="property-tag button alt featured">Referência: {{$item->reference}}</div>                                            
                                            @endif                                        
                                            <div class="price-ratings">
                                                <div class="price">
                                                    @if($item->display_values)
                                                        @php
                                                            $venda = ($item->sale_value && !empty($item->sale_value))
                                                                ? 'R$' . number_format($item->sale_value, 0, ',', '.')
                                                                : null;

                                                            $aluguel = ($item->rental_value && !empty($item->rental_value))
                                                                ? 'R$' . number_format($item->rental_value, 0, ',', '.') . '/'. $item->getLocationPeriod()
                                                                : null;
                                                        @endphp

                                                        @if($venda && $aluguel)
                                                            Venda: {{ $venda }} <br> Locação: {{ $aluguel }}
                                                        @elseif($venda)
                                                            Venda: {{ $venda }}
                                                        @elseif($aluguel)
                                                            Locação: {{ $aluguel }}
                                                        @else
                                                            Sob Consulta
                                                        @endif
                                                    @endif
                                                </div>                                            
                                            </div>                                                                       
                                            <img src="{{$item->cover()}}" alt="{{$item->title}}" class="img-responsive"/>            
                                            <div class="property-overlay">
                                                <a href="{{route('web.property',['slug' => $item->slug])}}" class="overlay-link">
                                                    <i class="fa fa-link"></i>
                                                </a>    
                                                @if($item->images()->get()->count())
                                                    <a href="{{$item->cover()}}" class="overlay-link"><i class="fa fa-expand"></i></a>
                                                    <div class="property-magnify-gallery"> 
                                                        @foreach($item->images()->get() as $image)                                  
                                                            <a href="{{ $image->url_image }}" class="hidden"></a> 
                                                        @endforeach
                                                    </div>
                                                @endif  
                                                @auth
                                                    @if(auth()->user()->canEditProperties())
                                                        <a 
                                                            href="{{ route('property.edit', $item->id) }}"
                                                            class="absolute top-2 right-2 z-50 bg-black/70 text-white hover:text-white px-4 py-3 rounded text-sm hover:bg-black"
                                                            title="Editar imóvel"
                                                        >
                                                            <i class="fa fa-pencil"></i>
                                                        </a>
                                                    @endif
                                                @endauth     
                                            </div>
                                    </div>
                                    <div class="content">
                                        <h4 class="title">
                                            <a href="{{route('web.property',['slug' => $item->slug])}}">{{$item->title}}</a>
                                        </h4>
                                        @if ($item->display_address)
                                            <h3 class="property-address">
                                                <a href="{{route('web.property',['slug' => $item->slug])}}">
                                                    <i class="fa fa-map-marker"></i> {{$item->neighborhood}}, {{$item->city}} / {{$item->state}}
                                                </a>
                                            </h3>
                                        @endif
                                        
                                    </div>
                                    <ul class="facilities-list clearfix">
                                        @if ($item->total_area)
                                            <li>
                                                <i class="flaticon-square-layouting-with-black-square-in-east-area"></i>
                                                <span>{{$item->total_area}} {{$item->measures}}</span>
                                            </li>                                            
                                        @endif
                                        @if ($item->dormitories)
                                            <li>
                                                <i class="flaticon-bed"></i>
                                                <span>{{$item->dormitories}}</span>
                                            </li>                                            
                                        @endif
                                        @if ($item->bathrooms)
                                            <li>
                                                <i class="flaticon-holidays"></i>
                                                <span>{{$item->bathrooms}}</span>
                                            </li>                                            
                                        @endif
                                        @if ($item->garage)
                                            <li>
                                                <i class="flaticon-vehicle"></i>
                                                <span>{{$item->garage}}</span>
                                            </li>                                            
                                        @endif                                        
                                    </ul>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{url(asset('frontend/'.$configuracoes->template.'/js/jsSocials/jssocials.css'))}}" />
    <link rel="stylesheet" type="text/css" href="{{url(asset('frontend/'.$configuracoes->template.'/js/jsSocials/jssocials-theme-flat.css'))}}" />
    <link rel="stylesheet" type="text/css" href="{{url(asset('frontend/'.$configuracoes->template.'/js/shadowbox/shadowbox.css'))}}"/>
@endsection

@section('js')
    <script src="{{asset('frontend/'.$configuracoes->template.'/js/jquery.magnific-popup.min.js')}}"></script>
    <script type="text/javascript" src="{{url(asset('frontend/'.$configuracoes->template.'/js/jsSocials/jssocials.min.js'))}}"></script>
    <script type="text/javascript" src="{{url(asset('frontend/'.$configuracoes->template.'/js/shadowbox/shadowbox.js'))}}"></script>
    <script>
        Shadowbox.init();
        
        $('#shareIcons').jsSocials({
            showLabel: false,
            showCount: false,
            shareIn: "popup",
            shares: ["email", "twitter", "facebook", "whatsapp"]
        });
    </script>
    <script>
        $(document).ready(function() {
            $('.overlay-link').on('click', function(e) {
                e.preventDefault(); // evita o comportamento padrão do link

                // seleciona o container de imagens ocultas
                const $gallery = $(this).siblings('.property-magnify-gallery');

                // inicializa o Magnific Popup
                $gallery.magnificPopup({
                    delegate: 'a',        // todos os <a> dentro do container
                    type: 'image',
                    gallery: { enabled: true }
                }).magnificPopup('open', 0); // abre a partir do primeiro link
            });
        });
    </script>
@endsection