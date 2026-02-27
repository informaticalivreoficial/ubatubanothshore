<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge"> 
        <meta name="language" content="pt-br" />  
        <meta name="copyright" content="{{$configuracoes->init_date}} - {{$configuracoes->app_name}}"> 
        
        <meta name="author" content="{{env('DESENVOLVEDOR')}}"/>
        <meta name="designer" content="Renato Montanari">
        <meta name="publisher" content="Renato Montanari">
        <meta name="url" content="{{$configuracoes->domain}}" />
        <meta name="keywords" content="{{$configuracoes->metatags}}">
        <meta name="distribution" content="web">
        <meta name="rating" content="general">
        <meta name="date" content="December 2018">      

        {!! $head ?? '' !!}        
                
        <!-- FAVICON -->
        <link rel="icon" type="image/png" href="{{$configuracoes->getfaveicon()}}" />
        <link rel="shortcut icon" href="{{$configuracoes->getfaveicon()}}" type="image/x-icon"/>  
        
        <!-- CSS -->
        
        <link rel="stylesheet" type="text/css" href="{{url(asset('frontend/'.$configuracoes->template.'/css/bootstrap.min.css'))}}"/>
        <link rel="stylesheet" type="text/css" href="{{url(asset('frontend/'.$configuracoes->template.'/css/animate.min.css'))}}"/>
        <link rel="stylesheet" type="text/css" href="{{url(asset('frontend/'.$configuracoes->template.'/css/bootstrap-submenu.css'))}}"/>
        <link rel="stylesheet" type="text/css" href="{{url(asset('frontend/'.$configuracoes->template.'/css/bootstrap-select.min.css'))}}"/>
        <link rel="stylesheet" type="text/css" href="{{url(asset('frontend/'.$configuracoes->template.'/css/leaflet.css'))}}"/>
        <link rel="stylesheet" type="text/css" href="{{url(asset('frontend/'.$configuracoes->template.'/css/map.css'))}}"/>
        <link rel="stylesheet" type="text/css" href="{{url(asset('frontend/'.$configuracoes->template.'/fonts/font-awesome/css/font-awesome.min.css'))}}"/>
        <link rel="stylesheet" type="text/css" href="{{url(asset('frontend/'.$configuracoes->template.'/fonts/flaticon/font/flaticon.css'))}}"/>
        <link rel="stylesheet" type="text/css" href="{{url(asset('frontend/'.$configuracoes->template.'/fonts/linearicons/style.css'))}}"/>
        <link rel="stylesheet" type="text/css"  href="{{url(asset('frontend/'.$configuracoes->template.'/css/jquery.mCustomScrollbar.css'))}}"/>
        <link rel="stylesheet" type="text/css"  href="{{url(asset('frontend/'.$configuracoes->template.'/css/dropzone.css'))}}"/>
        <link rel="stylesheet" type="text/css"  href="{{url(asset('frontend/'.$configuracoes->template.'/css/magnific-popup.css'))}}"/>

        <!-- Custom stylesheet -->
        <link rel="stylesheet" type="text/css" href="{{url(asset('frontend/'.$configuracoes->template.'/css/style.css'))}}"/>
        <link rel="stylesheet" type="text/css" href="{{url(asset('frontend/'.$configuracoes->template.'/css/renato.css'))}}"/>
        <link rel="stylesheet" type="text/css" href="{{url(asset('frontend/'.$configuracoes->template.'/css/skins/green-light.css'))}}"/>
        
        <!-- Google fonts -->
        <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800%7CPlayfair+Display:400,700%7CRoboto:100,300,400,400i,500,700">  
        
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        @hasSection('css')
            @yield('css')
        @endif    

    </head>
    <body>
        <!-- Top header start -->
        <header class="top-header hidden-xs" id="top">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                        <div class="list-inline">
                            @if ($configuracoes->cell_phone)
                                <a href="tel:{{$configuracoes->cell_phone}}"><i class="fa fa-phone"></i>{{$configuracoes->cell_phone}}</a>
                            @endif
                            @if ($configuracoes->whatsapp)
                                <a target="_blank" href="{{\App\Helpers\WhatsApp::getNumZap($configuracoes->whatsapp ,'Atendimento '.$configuracoes->name)}}"><i class="fa fa-whatsapp"></i>{{$configuracoes->whatsapp}}</a>
                            @endif
                            @if ($configuracoes->email)
                                <a href="mailto:{{$configuracoes->email}}"><i class="fa fa-envelope"></i>{{$configuracoes->email}}</a>                                
                            @endif
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="text-align: right;">
                        <a target="_blank" style="margin-top: 5px;margin-bottom: 5px;" href="{{route('web.simulator')}}" class="btn button-sm border-button-theme">financiamento</a>
                        <a style="margin-top: 5px;margin-bottom: 5px;margin-left: 10px;" href="{{route('web.pesquisar-imoveis')}}" class="btn button-sm border-button-theme">Buscar Imóveis</a>
                                               
                        <ul class="top-social-media pull-right" style="margin-left: 10px;">
                            @if ($configuracoes->facebook)
                                <li><a target="_blank" href="{{$configuracoes->facebook}}" class="facebook"><i class="fa fa-facebook"></i></a></li>                                
                            @endif
                            @if ($configuracoes->twitter)
                                <li><a target="_blank" href="{{$configuracoes->twitter}}" class="twitter"><i class="fa fa-twitter"></i></a></li>
                            @endif
                            @if ($configuracoes->linkedin)
                                <li><a target="_blank" href="{{$configuracoes->linkedin}}" class="linkedin"><i class="fa fa-linkedin"></i></a></li>                                
                            @endif
                            @if ($configuracoes->instagram)
                                <li><a target="_blank" href="{{$configuracoes->instagram}}" class="instagram"><i class="fa fa-instagram"></i></a></li>
                            @endif
                        </ul>

                    </div>
                </div>
            </div>
        </header>
    <!-- Top header end -->

    <!-- Main header start -->
    <header class="main-header">
        <div class="container">
            <nav class="navbar navbar-default">
                <div class="navbar-header">                
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navigation" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a href="{{route('web.home')}}" class="logo" title="{{$configuracoes->app_name}}">
                        <img src="{{$configuracoes->getlogo()}}" alt="{{$configuracoes->app_name}}"/>
                    </a>
                    <ul class="top-social-media mobile">
                        @if ($configuracoes->facebook)
                            <li><a target="_blank" href="{{$configuracoes->facebook}}" class="facebook"><i class="fa fa-facebook"></i></a></li>                                
                        @endif
                        @if ($configuracoes->twitter)
                            <li><a target="_blank" href="{{$configuracoes->twitter}}" class="twitter"><i class="fa fa-twitter"></i></a></li>
                        @endif
                        @if ($configuracoes->linkedin)
                            <li><a target="_blank" href="{{$configuracoes->linkedin}}" class="linkedin"><i class="fa fa-linkedin"></i></a></li>                                
                        @endif
                        @if ($configuracoes->instagram)
                            <li><a target="_blank" href="{{$configuracoes->instagram}}" class="instagram"><i class="fa fa-instagram"></i></a></li>
                        @endif
                    </ul>
                </div>
                <!-- MENU -->
                <div class="navbar-collapse collapse" role="navigation" aria-expanded="true" id="app-navigation">
                    <ul class="nav navbar-nav">                         
                        @if (!empty($Links) && $Links->count())                            
                            @foreach($Links as $menuItem) 
                                <li {{($menuItem->children && $menuItem->parent ? 'class=dropdown' : '')}}>
                                    <a 
                                        {{($menuItem->target == 1 ? 'target=_blank' : 'target=_self')}} 
                                        target="_self" 
                                        href="{{($menuItem->type == 'pagina' ? route('web.page', [ 'slug' => ($menuItem->post != null ? $menuItem->PostObject->slug : '#') ]) : $menuItem->url)}}">
                                        {{ $menuItem->title }}{!!($menuItem->children && $menuItem->parent ? "<span class=\"caret\"></span>" : '')!!}
                                    </a>
                                    @if( $menuItem->children && $menuItem->parent)
                                        <ul class="dropdown-menu">
                                            @foreach($menuItem->children as $subMenuItem)
                                            <li class="dropdown-submenu">
                                                <a {{($subMenuItem->target == 1 ? 'target=_blank' : 'target=_self')}} href="{{($subMenuItem->tipo == 'Página' ? route('web.page', [ 'slug' => ($subMenuItem->post != null ? $subMenuItem->PostObject->slug : '#') ]) : $subMenuItem->url)}}">{{ $subMenuItem->title }}</a>
                                            </li>                                        
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endforeach
                        @endif
                        <li class="dropdown">
                            <a title="Imóveis" class="nav-link dropdown-toggle" href="javascript:void(0)" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Imóveis
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-submenu" href="{{route('web.propertylist', ['type' => 'venda'])}}" title="Comprar">Comprar</a></li>
                                <li><a class="dropdown-submenu" href="{{route('web.propertylist', ['type' => 'locacao'])}}" title="Alugar">Alugar</a></li>
                            </ul>
                        </li>
                        @if (!empty($lancamentoMenu) && $lancamentoMenu->count() > 0)
                            <li><a href="{{route('web.highliths')}}" title="Lançamentos">Lançamentos</a></li>
                        @endif 
                        <li><a href="{{route('web.contact')}}">Atendimento</a></li>                        
                    </ul>
                    
                    {{--
                    <ul class="nav navbar-nav navbar-right rightside-navbar">
                        <li>
                            <a href="" class="button">
                                Cadastre seu Imóvel
                            </a>
                        </li>
                    </ul>
                    --}}
                </div>
            </nav>
        </div>
    </header>
    <!-- Main header end -->    

    @yield('content')

    <!-- Footer start -->
<!-- Footer start -->
<footer class="bg-gray-900 text-gray-100 py-24">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-8">
            <!-- Atendimento: ocupa mais espaço -->
            <div class="lg:col-span-5">
                <h2 class="text-md font-bold mb-5 text-gray-100">Atendimento</h2>
                <p class="mb-5 text-md text-gray-200">{{ $configuracoes->information }}</p>
                <ul class="space-y-4 text-md text-gray-200">
                    @if ($configuracoes->display_address)
                        <li class="flex items-start gap-2">
                            <i class="fa fa-map-marker mt-1"></i>
                            <span>
                                @if ($configuracoes->street) {{ $configuracoes->street }} @endif
                                @if ($configuracoes->number) , {{ $configuracoes->number }} @endif
                                @if ($configuracoes->neighborhood) , {{ $configuracoes->neighborhood }} @endif
                                @if ($configuracoes->city) - {{ $configuracoes->city }} @endif
                                @if ($configuracoes->state) / {{ $configuracoes->state }} @endif
                            </span>
                        </li>
                    @endif
                    @if ($configuracoes->email)
                        <li class="flex items-center gap-2">
                            <i class="fa fa-envelope"></i>
                            <a href="mailto:{{$configuracoes->email}}" class="text-gray-200 hover:text-teal-400">{{ $configuracoes->email }}</a>
                        </li>
                    @endif
                    @if ($configuracoes->additional_email)
                        <li class="flex items-center gap-2">
                            <i class="fa fa-envelope"></i>
                            <a href="mailto:{{$configuracoes->additional_email}}" class="text-gray-200 hover:text-teal-400">{{ $configuracoes->additional_email }}</a>
                        </li>
                    @endif
                    @if ($configuracoes->phone)
                        <li class="flex items-center gap-2">
                            <i class="fa fa-phone"></i>
                            <a href="tel:{{$configuracoes->phone}}" class="text-gray-200 hover:text-teal-400">{{ $configuracoes->phone }}</a>
                        </li>
                    @endif
                    @if ($configuracoes->cell_phone)
                        <li class="flex items-center gap-2">
                            <i class="fa fa-phone"></i>
                            <a href="tel:{{$configuracoes->cell_phone}}" class="text-gray-200 hover:text-teal-400">{{ $configuracoes->cell_phone }}</a>
                        </li>
                    @endif
                    @if ($configuracoes->whatsapp)
                        <li class="flex items-center gap-2">
                            <i class="fa fa-whatsapp"></i>
                            <a target="_blank" href="{{ \App\Helpers\WhatsApp::getNumZap($configuracoes->whatsapp, 'Atendimento '.$configuracoes->app_name) }}" class="text-gray-200 hover:text-teal-400">{{ $configuracoes->whatsapp }}</a>
                        </li>
                    @endif
                </ul>
            </div>

            <!-- Links -->
            <div class="lg:col-span-3">
                <h2 class="text-md font-bold mb-5 text-gray-100">Links</h2>
                <ul class="space-y-2 text-md text-gray-200">
                    <li><a href="{{ route('web.home') }}" class="text-gray-200 hover:text-teal-400">Início</a></li>
                    <li><a href="{{ route('web.blog.index') }}" class="text-gray-200 hover:text-teal-400">Blog</a></li>
                    <li><a href="{{route('web.properties')}}" class="text-gray-200 hover:text-teal-400">Imóveis</a></li>
                    <li><a target="_blank" href="{{route('web.simulator')}}" class="text-gray-200 hover:text-teal-400">Financiamento</a></li>
                    <li><a href="{{route('web.pesquisar-imoveis')}}" class="text-gray-200 hover:text-teal-400">Buscar Imóvel</a></li>
                    @if (!empty($lancamentoMenu) && $lancamentoMenu->count() > 0)
                        <li><a class="text-gray-200 hover:text-teal-400" href="{{route('web.highliths')}}" title="Lançamentos">Lançamentos</a></li>
                    @endif
                    @if ($configuracoes->privacy_policy)
                        <li><a href="{{route('web.privacy')}}" class="text-gray-200 hover:text-teal-400">Política de Privacidade</a></li>                        
                    @endif
                    <li><a href="{{route('web.contact')}}" class="text-gray-200 hover:text-teal-400">Atendimento</a></li>
                </ul>
            </div>

            <!-- Blog -->
            <div class="lg:col-span-4">
                <h2 class="text-md font-bold mb-5 text-gray-100">Blog</h2>
                <div class="space-y-8">
                    @if($postsfooter && $postsfooter->count())
                        @foreach($postsfooter as $blog)
                            @php
                                $tipo = $blog->type == 'noticia' ? 'noticia' : 'artigo';
                            @endphp
                            <div class="flex items-start gap-4 mb-8">
                                <div class="flex-shrink-0">
                                    <img src="{{ $blog->cover() }}" alt="{{ $blog->title }}" class="w-24 h-24 object-cover rounded">
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-md font-semibold text-teal-400 hover:text-gray-400 transition">
                                        <a href="{{ route('web.blog.'.$tipo,['slug' => $blog->slug]) }}">
                                            {{ $blog->title }}
                                        </a>
                                    </h3>
                                    <p class="text-sm text-gray-300 mt-1">{{ $blog->created_at->format('d M, Y') }}</p>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- Footer end -->

    <!-- Copy right start -->
    <div class="copy-right">
        <div class="container">
            <div class="row clearfix">
                <div class="col-md-8 col-sm-12">
                    &copy;  {{$configuracoes->init_date}} {{$configuracoes->app_name}} - Todos os direitos reservados.
                </div>
                <div class="col-md-4 col-sm-12">
                    <ul class="social-list clearfix">
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
                    <span class="small text-silver-dark">Feito com <i style="color:red;" class="fa fa-heart"></i> por <a style="color:#fff;" target="_blank" href="{{env('DESENVOLVEDOR_URL')}}">{{env('DESENVOLVEDOR')}}</a></span>                    
                </div>
            </div>
        </div>
    </div>
    <!-- Copy end right-->   

    <script type="text/javascript" src="{{url(asset('frontend/'.$configuracoes->template.'/js/jquery-2.2.0.min.js'))}}"></script>
    <script type="text/javascript" src="{{url(asset('frontend/'.$configuracoes->template.'/js/jquery.form.js'))}}"></script>
    <script type="text/javascript" src="{{url(asset('frontend/'.$configuracoes->template.'/js/bootstrap.min.js'))}}"></script>
    <script type="text/javascript" src="{{url(asset('frontend/'.$configuracoes->template.'/js/bootstrap-submenu.js'))}}"></script>
    <script type="text/javascript" src="{{url(asset('frontend/'.$configuracoes->template.'/js/rangeslider.js'))}}"></script>
    <script type="text/javascript" src="{{url(asset('frontend/'.$configuracoes->template.'/js/jquery.mb.YTPlayer.js'))}}"></script>
    <script type="text/javascript" src="{{url(asset('frontend/'.$configuracoes->template.'/js/wow.min.js'))}}"></script>
    <script type="text/javascript" src="{{url(asset('frontend/'.$configuracoes->template.'/js/bootstrap-select.min.js'))}}"></script>
    <script type="text/javascript" src="{{url(asset('frontend/'.$configuracoes->template.'/js/jquery.easing.1.3.js'))}}"></script>
    <script type="text/javascript" src="{{url(asset('frontend/'.$configuracoes->template.'/js/jquery.scrollUp.js'))}}"></script>
    <script type="text/javascript" src="{{url(asset('frontend/'.$configuracoes->template.'/js/jquery.mCustomScrollbar.concat.min.js'))}}"></script>
    <script type="text/javascript" src="{{url(asset('frontend/'.$configuracoes->template.'/js/leaflet.js'))}}"></script>
    <script type="text/javascript" src="{{url(asset('frontend/'.$configuracoes->template.'/js/leaflet-providers.js'))}}"></script>
    <script type="text/javascript" src="{{url(asset('frontend/'.$configuracoes->template.'/js/leaflet.markercluster.js'))}}"></script>
    <script type="text/javascript" src="{{url(asset('frontend/'.$configuracoes->template.'/js/dropzone.js'))}}"></script>
    <script type="text/javascript" src="{{url(asset('frontend/'.$configuracoes->template.'/js/jquery.filterizr.js'))}}"></script>
    <script type="text/javascript" src="{{url(asset('frontend/'.$configuracoes->template.'/js/maps.js'))}}"></script>
    <script type="text/javascript" src="{{url(asset('frontend/'.$configuracoes->template.'/js/app.js'))}}"></script>


    

    <!-- Máscara de Inputs -->
    <script type="text/javascript" src="{{url(asset('frontend/'.$configuracoes->template.'/js/jquery.maskedinput.min.js'))}}"></script>
    <script type="text/javascript" src="{{url(asset('frontend/'.$configuracoes->template.'/js/jquery.maskMoney.min.js'))}}"></script>

    
    
    @hasSection('js')
        @yield('js')
    @endif

        <script type="text/javascript">
            (function ($) {

                // WOW animation library initialization
                var wow = new WOW(
                    {
                        animateClass: 'animated',
                        offset: 100,
                        mobile: false
                    }
                );
                wow.init();

                // Multilevel menuus
                $('[data-submenu]').submenupicker();

                

                // Background video playing script
                $(document).ready(function () {
                    $(".player").mb_YTPlayer();
                });

            })(jQuery);   
                
        </script>
        
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-N73T2G5HFS"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', 'G-N73T2G5HFS');
        </script>
    </body>
</html>