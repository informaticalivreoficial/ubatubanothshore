<header class="fixed top-0 left-0 right-0 z-50 transition-all duration-300" id="navbar">
    <div class="bg-[#efebe0] mx-auto px-6 py-3 flex items-center justify-between">
        {{-- Logo --}}
        <a href="{{ route('web.home') }}" class="flex items-center gap-3">
            <img src="{{ $configuracoes->getlogo() }}" alt="{{ $configuracoes->app_name }}" class="h-20 object-contain">
        </a>

        {{-- Nav links --}}
        <nav class="hidden md:flex items-center gap-8 text-slate-800">
            <a href="{{ route('web.home') }}" class="nav-link text-sm font-medium">Início</a>
            <a href="{{ route('web.properties') }}" class="nav-link text-sm font-medium">Todos os imóveis</a>
            <a href="/about-us" class="nav-link text-sm font-medium">Sobre nós</a>            
        </nav>

        {{-- Mobile menu button --}}
        <button class="md:hidden text-white" id="mobile-menu-btn">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
    </div>

    {{-- Mobile menu --}}
    <div class="hidden md:hidden bg-primary/95 backdrop-blur-md px-6 py-4 space-y-3" id="mobile-menu">
        <a href="/" class="block text-white text-sm py-2 border-b border-white/10">Início</a>
        <a href="/all-listings" class="block text-white text-sm py-2 border-b border-white/10">Todas as listagens</a>
        <a href="/about-us" class="block text-white text-sm py-2">Sobre nós</a>
    </div>
</header>