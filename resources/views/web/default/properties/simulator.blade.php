<html lang="pt-br">
<head>
    <meta charset="UTF-8">        
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="copyright" content="{{$configuracoes->init_date}} - {{$configuracoes->app_name}}">       
    <meta name="author" content="{{env('DESENVOLVEDOR')}}">
    <meta name="language" content="pt-br">
    <meta name="designer" content="Renato Montanari">
    <meta name="publisher" content="Renato Montanari">
    <meta name="url" content="{{$configuracoes->domain}}" />
    <meta name="keywords" content="{{$configuracoes->metatags}}">
    <meta name="distribution" content="web">
    <meta name="rating" content="general">
    <meta name="date" content="December 2018">
            
    <!-- FAVICON -->
    <link rel="icon" type="image/png" href="https://imobiliariaubatuba.com/uploads/logomarca/favicon-imoveis-em-ubatuba.png">
    <link rel="shortcut icon" href="https://imobiliariaubatuba.com/uploads/logomarca/favicon-imoveis-em-ubatuba.png" type="image/x-icon">        

    <title>Simular Financiamento - {{$configuracoes->app_name}}</title> 
    <meta name="description" content="{{$configuracoes->information}}">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{route('web.simulator')}}" />

    <meta property="og:site_name" content="{{$configuracoes->app_name}}">
    <meta property="og:locale" content="pt_BR">
    <meta property="og:locale:alternate" content="en_US">
    <meta property="og:title" content="Simular Financiamento - {{$configuracoes->app_name}}">
    <meta property="og:description" content="Simule aqui o seu financiamento imobiliário com as melhores condições do mercado.">
    <meta property="og:image" content="{{$configuracoes->getmetaimg()}}">
    <meta property="og:url" content="{{route('web.simulator')}}">
    <meta property="og:image:type" content="image/jpeg">
    <meta property="og:image:width" content="200">
    <meta property="og:image:height" content="200">
    <meta property="og:type" content="website">

    <meta itemprop="name" content="Simular Financiamento - {{$configuracoes->app_name}}">
    <meta itemprop="description" content="Simule aqui o seu financiamento imobiliário com as melhores condições do mercado.">
    <meta itemprop="url" content="{{route('web.simulator')}}">
            
    <!-- FAVICON -->
    <link rel="icon" type="image/png" href="{{$configuracoes->getfaveicon()}}" />
    <link rel="shortcut icon" href="{{$configuracoes->getfaveicon()}}" type="image/x-icon"/>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
</head>
    <body class="bg-gray-100">
        <div class="pt-4 flex justify-center">
            <img src="{{$configuracoes->getlogo()}}" alt="{{$configuracoes->app_name}}"/>
        </div> 

        <livewire:web.property-simulator  />

        <div class="px-4 py-4">
            <img width="100" src="{{url(asset('frontend/'.$configuracoes->template.'/images/site-seguro.png'))}}" alt="Site 100% Seguro" title="Site 100% Seguro" /></a>
        </div>

        <a id="page_scroller" href="#top" style="display: none; position: fixed; z-index: 2147483647;"><i class="fa fa-chevron-up"></i></a>
    </body>
</html>