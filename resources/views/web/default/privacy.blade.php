 
@extends("web.$configuracoes->template.master.master")

@section('content')
    <div class="sub-banner overview-bgi" style="background: rgba(0, 0, 0, 0.04) url({{$configuracoes->getheadersite()}}) top left repeat;">
        <div class="overlay">
            <div class="container">
                <div class="breadcrumb-area">
                    <h1 style="font-size: 36px;">Política de Privacidade</h1>
                    <ul class="breadcrumbs">
                        <li><a href="{{route('web.home')}}">Início</a></li>
                        <li class="active">Política de Privacidade</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="blog-body content-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="thumbnail blog-box clearfix">
                        <div class="caption detail">
                            <h3 class="title">
                                Política de Privacidade
                            </h3>           
                            {!!$configuracoes->privacy_policy!!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection