@extends("web.$configuracoes->template.master.master")

@section('content')
    <div class="sub-banner overview-bgi" style="background: rgba(0, 0, 0, 0.04) url({{$configuracoes->getheadersite()}}) top left repeat;">
        <div class="overlay">
            <div class="container">
                <div class="breadcrumb-area">
                    <h1 style="font-size: 36px;">{{$page->title}}</h1>
                    <ul class="breadcrumbs">
                        <li><a href="{{route('web.home')}}">Início</a></li>
                        <li class="active">{{$page->title}}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="blog-body content-area">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-push-4">
                    <!-- Blog box start -->
                    <div class="thumbnail blog-box clearfix">                
                        <div class="caption detail">
                            <!--Main title -->
                            <h3 class="title">
                                {{$page->title}}
                            </h3>
                            <!-- Post meta -->
                            <div class="post-meta">
                                <span><a href="#"><i class="fa fa-user"></i>{{$page->user->name}}</a></span>
                                <span><a><i class="fa fa-calendar "></i>{{ $page->created_at->format('d M, Y') }}</a></span>
                            </div>
                            <!-- Social list -->
                            <div id="shareIcons"></div>
                            {!!$page->content!!}
                            
                            @if ($page->images()->get()->count())
                                <div class="row clearfix t-s">                            
                                    @foreach($page->images()->get() as $gb)
                                        <div class="col-lg-2 col-md-2 col-sm-3 col-xs-6">
                                            <div class="agent-1">
                                                <a rel="ShadowBox[galeria]" href="{{ $gb->url_image }}" class="agent-img">
                                                    <img src="{{ $gb->url_cropped }}" alt="{{ $page->titulo }}" class="img-responsive">
                                                </a>
                                            </div>                                
                                        </div>
                                    @endforeach                          
                                </div>
                            @endif                        
                            
                            <div class="row clearfix t-s">                                
                                <div class="col-12 col-xs-12">                                    
                                    <div class="social-media clearfix blog-share">
                                        <h2>Compartilhe</h2>                                        
                                        <div class="shareIcons"></div>                                    
                                    </div>                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
    <script type="text/javascript" src="{{url(asset('frontend/'.$configuracoes->template.'/js/jsSocials/jssocials.min.js'))}}"></script>
    <script type="text/javascript" src="{{url(asset('frontend/'.$configuracoes->template.'/js/shadowbox/shadowbox.js'))}}"></script>
    <script>
        Shadowbox.init();
        
        $('.shareIcons').jsSocials({
            //url: "http://www.google.com",
            showLabel: false,
            showCount: false,
            shareIn: "popup",
            shares: ["email", "twitter", "facebook", "whatsapp"]
        });
    </script>
@endsection