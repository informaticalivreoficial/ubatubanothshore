<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="language" content="pt-br" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="copyright" content="{{$configuracoes->init_date}} - {{$configuracoes->app_name}}">
    
    {!! $head ?? '' !!}     
    
    <meta name="author" content="{{env('DESENVOLVEDOR')}}"/>
    <meta name="designer" content="Renato Montanari">
    <meta name="publisher" content="Renato Montanari">
    <meta name="url" content="{{ $configuracoes->domain }}" />
    <meta name="keywords" content="{{ $configuracoes->metatags }}">
    <meta name="distribution" content="web">
    <meta name="rating" content="general">
    <meta name="date" content="December 2018"> 

    <!-- FAVICON -->
    <link rel="icon" type="image/png" href="{{$configuracoes->getfaveicon()}}" />
    <link rel="shortcut icon" href="{{$configuracoes->getfaveicon()}}" type="image/x-icon"/> 

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500;600&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    
    <style>
        body { font-family: 'DM Sans', sans-serif; color: #1a2e1a; }           
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="min-h-screen flex flex-col bg-[#f8f8f8]">

    {{-- NAVBAR --}}
    @include('web.components.header')    

    {{-- PAGE CONTENT --}}
    <main class="pt-24">
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    {{-- FOOTER --}}
    @include('web.components.footer')  
    
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/pt.js"></script>

    

    <script>
        const mobileMenu = document.getElementById('mobile-menu');
        const closeBtn = document.getElementById('close-mobile');

        function openMobile() {
            mobileMenu.classList.remove('translate-x-full');
        }

        function closeMobile() {
            mobileMenu.classList.add('translate-x-full');
        }

        closeBtn.addEventListener('click', closeMobile);

        function toggleSubmenu(id) {
            const submenu = document.getElementById('submenu-' + id);
            const arrow = document.getElementById('arrow-' + id);

            submenu.classList.toggle('hidden');
            arrow.classList.toggle('rotate-45');
        }
    </script>
    @stack('scripts')
</body>
</html>