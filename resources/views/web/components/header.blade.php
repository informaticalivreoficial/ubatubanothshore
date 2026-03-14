<header id="navbar" class="fixed top-0 left-0 right-0 z-50 bg-[#efebe0] shadow-sm transition-all duration-300">

    <div class="mx-auto px-6 py-4 flex items-center justify-between">

        <!-- Logo -->
        <a href="{{ route('web.home') }}">
            <img src="{{ $configuracoes->getlogo() }}" 
                 alt="{{ $configuracoes->app_name }}" 
                 class="h-16 object-contain">
        </a>

        <!-- Desktop Menu -->
        <nav class="hidden md:flex items-center gap-8 text-slate-800 text-sm font-medium">
            <a href="{{ route('web.home') }}" target="_self" class="hover:text-primary transition font-bold">
                Início
            </a>
            <a href="{{ route('web.properties') }}" target="_self" class="hover:text-primary transition font-bold">
                Todos os Imóveis
            </a>            
            @if (!empty($Links) && $Links->count())                            
                @foreach($Links as $menuItem)

                    @php
                        $hasChildren = $menuItem->children && $menuItem->children->count();
                        $url = ($menuItem->type == 'pagina'
                                ? route('web.page', ['slug' => ($menuItem->post != null ? $menuItem->PostObject->slug : '#')])
                                : $menuItem->url);
                    @endphp

                    @if(!$hasChildren)

                        <a href="{{ $url }}"
                        target="{{ $menuItem->target == 1 ? '_blank' : '_self' }}"
                        class="hover:text-primary transition font-bold">
                            {{ $menuItem->title }}
                        </a>

                    @else

                        <div class="relative group">

                            <button class="flex items-center gap-1 hover:text-primary transition py-2">
                                {{ $menuItem->title }}

                                <svg class="w-4 h-4 transition-transform duration-200 group-hover:rotate-180"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <div class="absolute left-0 top-full pt-3 invisible opacity-0 
                                        group-hover:visible group-hover:opacity-100
                                        transition-all duration-200">

                                <div class="w-56 bg-white shadow-xl rounded-xl border border-gray-100">

                                    @foreach($menuItem->children as $subMenuItem)

                                        @php
                                            $subUrl = ($subMenuItem->type == 'pagina'
                                                ? route('web.page', ['slug' => ($subMenuItem->post != null ? $subMenuItem->PostObject->slug : '#')])
                                                : $subMenuItem->url);
                                        @endphp

                                        <a href="{{ $subUrl }}"
                                        target="{{ $subMenuItem->target == 1 ? '_blank' : '_self' }}"
                                        class="block px-4 py-3 hover:bg-gray-50 first:rounded-t-xl last:rounded-b-xl">
                                            {{ $subMenuItem->title }}
                                        </a>

                                    @endforeach

                                </div>
                            </div>

                        </div>

                    @endif

                @endforeach
            @endif

            <a href="{{ route('web.blog.index') }}" target="_self" class="hover:text-primary transition font-bold">
                Blog
            </a>

            <a href="{{ route('web.contact') }}" target="_self" class="hover:text-primary transition font-bold">
                Atendimento
            </a>


            <!-- Redes sociais -->
            <div class="flex items-center gap-4 ml-6 border-l pl-6">

                @if ($configuracoes->facebook)
                    <a href="{{ $configuracoes->facebook }}" target="_blank"
                    class="text-slate-600 hover:text-[#1877F2] transition">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                            <path d="M22 12a10 10 0 10-11.63 9.87v-6.99h-2.8V12h2.8V9.8c0-2.76 1.64-4.3 4.15-4.3 1.2 0 2.45.21 2.45.21v2.7h-1.38c-1.36 0-1.78.84-1.78 1.7V12h3.03l-.48 2.88h-2.55v6.99A10 10 0 0022 12z"/>
                        </svg>
                    </a>
                @endif

                @if ($configuracoes->instagram)
                    <a href="{{ $configuracoes->instagram }}" target="_blank"
                    class="text-slate-600 hover:text-[#E4405F] transition">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                            <path d="M7.75 2h8.5A5.75 5.75 0 0122 7.75v8.5A5.75 5.75 0 0116.25 22h-8.5A5.75 5.75 0 012 16.25v-8.5A5.75 5.75 0 017.75 2zm4.25 5a5 5 0 100 10 5 5 0 000-10zm6-1.75a1.25 1.25 0 110 2.5 1.25 1.25 0 010-2.5z"/>
                        </svg>
                    </a>
                @endif

            </div>

        </nav>

        <!-- Mobile Button -->
        <button onclick="openMobile()" class="md:hidden">
            <svg class="w-7 h-7 text-slate-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>

    </div>

    <!-- MOBILE MENU -->
    <div id="mobile-menu"
        class="fixed inset-0 bg-white z-40 transform translate-x-full transition-transform duration-300 md:hidden overflow-y-auto">

        <div class="p-6 space-y-6">

            <!-- Close -->
            <button id="close-mobile" class="mb-6 text-2xl">
                ✕
            </button>

            <a href="{{ route('web.home') }}" target="_self" class="block text-lg font-medium border-b pb-3">
                Início
            </a>
            <a href="{{ route('web.properties') }}" target="_self" class="block text-lg font-medium border-b pb-3">
                Todos os Imóveis
            </a>

            @if (!empty($Links) && $Links->count())                            
                @foreach($Links as $menuItem)

                    <!-- ITEM -->
                    <div class="border-b pb-3">

                        @if($menuItem->children && $menuItem->children->count())
                            
                            <!-- BOTÃO COM SUBMENU -->
                            <button onclick="toggleSubmenu({{ $menuItem->id }})"
                                    class="w-full flex justify-between items-center text-lg font-medium">

                                {{ $menuItem->title }}

                                <span id="arrow-{{ $menuItem->id }}" 
                                    class="transition-transform duration-300">
                                    +
                                </span>
                            </button>

                            <!-- SUBMENU -->
                            <div id="submenu-{{ $menuItem->id }}" 
                                class="hidden mt-3 space-y-3 pl-4">

                                @foreach($menuItem->children as $subMenuItem)
                                    <a 
                                    href="{{ ($subMenuItem->tipo == 'Página'
                                            ? route('web.page', ['slug' => ($subMenuItem->post != null ? $subMenuItem->PostObject->slug : '#')])
                                            : $subMenuItem->url) }}"
                                    class="block text-base text-slate-600 hover:text-primary transition">
                                        {{ $subMenuItem->title }}
                                    </a>
                                @endforeach

                            </div>

                        @else

                            <!-- LINK NORMAL -->
                            <a 
                            href="{{ ($menuItem->type == 'pagina'
                                    ? route('web.page', ['slug' => ($menuItem->post != null ? $menuItem->PostObject->slug : '#')])
                                    : $menuItem->url) }}"
                            class="block text-lg font-medium">
                                {{ $menuItem->title }}
                            </a>

                        @endif

                    </div>

                @endforeach
            @endif

            <a href="{{ route('web.blog.index') }}" target="_self" class="block text-lg font-medium border-b pb-3">
                Blog
            </a>
            
            <a href="{{ route('web.contact') }}" target="_self" class="block text-lg font-medium border-b pb-3">
                Atendimento
            </a>


            <!-- SOCIAL -->
            <div class="flex gap-5">

                @if ($configuracoes->facebook)
                    <a href="{{ $configuracoes->facebook }}" target="_blank"
                    class="text-slate-600 hover:text-[#1877F2] transition">
                        <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                            <path d="M22 12a10 10 0 10-11.63 9.87v-6.99h-2.8V12h2.8V9.8c0-2.76 
                            1.64-4.3 4.15-4.3 1.2 0 2.45.21 2.45.21v2.7h-1.38c-1.36 
                            0-1.78.84-1.78 1.7V12h3.03l-.48 2.88h-2.55v6.99A10 
                            10 0 0022 12z"/>
                        </svg>
                    </a>
                @endif

                @if ($configuracoes->instagram)
                    <a href="{{ $configuracoes->instagram }}" target="_blank"
                    class="text-slate-600 hover:text-[#E4405F] transition">
                        <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                            <path d="M7.75 2h8.5A5.75 5.75 0 0122 
                            7.75v8.5A5.75 5.75 0 0116.25 
                            22h-8.5A5.75 5.75 0 
                            012 16.25v-8.5A5.75 5.75 
                            0 017.75 2zm4.25 5a5 
                            5 0 100 10 5 5 0 
                            000-10zm6-1.75a1.25 
                            1.25 0 110 2.5 1.25 
                            1.25 0 010-2.5z"/>
                        </svg>
                    </a>
                @endif

            </div>

        </div>
    </div>

</header>