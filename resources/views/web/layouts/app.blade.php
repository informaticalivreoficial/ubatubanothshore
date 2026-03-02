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
        h1,h2,h3,h4 { font-family: 'Cormorant Garamond', serif; }

        .nav-link {
            position: relative;
            transition: color 0.2s;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 1px;
            background: #c9a96e;
            transition: width 0.3s ease;
        }
        .nav-link:hover::after { width: 100%; }
        .nav-link:hover { color: #c9a96e; }

        .card-img { overflow: hidden; }
        .card-img img { transition: transform 0.6s ease; }
        .card-img:hover img { transform: scale(1.05); }

        .amenity-tag {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 0.72rem;
            color: #5a6a5a;
            border: 1px solid #d5cfc5;
            padding: 2px 8px;
            border-radius: 99px;
        }

        .hero-overlay {
            background: linear-gradient(to bottom, rgba(26,46,26,0.45) 0%, rgba(26,46,26,0.1) 60%, rgba(248,245,240,1) 100%);
        }

        .search-bar {
            backdrop-filter: blur(12px);
            background: rgba(248, 245, 240, 0.92);
            border: 1px solid rgba(201,169,110,0.3);
        }

        input[type="date"]::-webkit-calendar-picker-indicator { opacity: 0.5; cursor: pointer; }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .fade-up { animation: fadeUp 0.7s ease forwards; }
        .fade-up-delay-1 { animation-delay: 0.15s; opacity: 0; animation-fill-mode: forwards; animation-name: fadeUp; animation-duration: 0.7s; }
        .fade-up-delay-2 { animation-delay: 0.30s; opacity: 0; animation-fill-mode: forwards; animation-name: fadeUp; animation-duration: 0.7s; }
        .fade-up-delay-3 { animation-delay: 0.45s; opacity: 0; animation-fill-mode: forwards; animation-name: fadeUp; animation-duration: 0.7s; }
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
        // Navbar scroll effect
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 60) {
                navbar.classList.add('bg-primary', 'shadow-lg');
                navbar.classList.remove('bg-transparent');
            } else {
                navbar.classList.remove('bg-primary', 'shadow-lg');
                navbar.classList.add('bg-transparent');
            }
        });
        // Start transparent
        navbar.classList.add('bg-transparent');

        // Mobile menu toggle
        document.getElementById('mobile-menu-btn')?.addEventListener('click', () => {
            document.getElementById('mobile-menu')?.classList.toggle('hidden');
        });
    </script>
    @stack('scripts')
</body>
</html>