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
<body class="min-h-screen flex flex-col bg-[#f8f8f8]" x-data="cookieConsent">

    {{-- NAVBAR --}}
    @include('web.components.header')    

    {{-- PAGE CONTENT --}}
    <main class="pt-24">
        {{ $slot ?? '' }}
        @yield('content')
    </main>    

    {{-- FOOTER --}}
    @include('web.components.footer')  

    
        <!-- BANNER -->
        <div 
            x-show="!accepted"
            class="fixed bottom-0 left-0 right-0 bg-gray-900 text-white p-4 z-40"
        >
            <div class="max-w-7xl mx-auto flex justify-between items-center">
                <p>
                    Utilizamos cookies para melhorar sua experiência.
                </p>

                <div class="flex gap-3">
                    <button @click="acceptAll()" class="bg-green-600 px-4 py-2 rounded">
                        Aceitar todos
                    </button>

                    <button @click="openModal()" class="bg-gray-600 px-4 py-2 rounded">
                        Preferências
                    </button>
                </div>
            </div>
        </div>

        <!-- MODAL -->
        <div 
            x-show="open"
            x-transition
            class="fixed inset-0 bg-black/50 flex items-center justify-center z-50"
            @click.self="closeModal()"
        >
            <div class="bg-white text-black p-6 rounded w-96 relative">
                
                <button 
                    @click="closeModal()" 
                    class="absolute top-2 right-2 text-gray-500"
                >
                    ✕
                </button>

                <h2 class="text-lg font-bold mb-4">Preferências de Cookies</h2>

                <label class="block mb-2">
                    <input type="checkbox" checked disabled>
                    Essenciais
                </label>

                <label class="block mb-2">
                    <input type="checkbox" x-model="stats">
                    Estatísticos
                </label>

                <label class="block mb-4">
                    <input type="checkbox" x-model="marketing">
                    Marketing
                </label>

                <button 
                    @click="save()" 
                    class="bg-blue-600 text-white px-4 py-2 rounded w-full"
                >
                    Salvar preferências
                </button>
            </div>
        </div>
    
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

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-G2Z3Y27L1S"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-G2Z3Y27L1S');
    </script>

    @stack('scripts')
</body>
</html>