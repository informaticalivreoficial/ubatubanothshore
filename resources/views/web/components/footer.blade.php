<footer class="bg-[#051e34] text-white">
    <div class="max-w-7xl mx-auto px-6 py-12">
        <div class="flex flex-col md:flex-row items-center justify-between gap-6">
            {{-- Logo --}}
            <a href="{{ route('web.home') }}">
                <img src="{{ $configuracoes->getlogofooter() }}"
                        alt="Catete Apartments"
                        class="h-20 object-contain "/>
            </a>

            {{-- Links --}}
            <nav class="flex flex-wrap justify-center gap-6 text-sm text-white/70">
                <a href="/privacy-policy" class="hover:text-accent transition-colors">Política de Privacidade</a>
                <a href="/terms-and-conditions" class="hover:text-accent transition-colors">Termos e Condições</a>
                <button class="hover:text-accent transition-colors">Preferências de cookies</button>
            </nav>

            {{-- Contact --}}
            @if ($configuracoes->email)
                <a href="mailto:{{ $configuracoes->email }}"
                    class="text-sm text-white/70 hover:text-accent transition-colors">
                    {{ $configuracoes->email }}
                </a>
            @endif            
        </div>

        <div class="mt-8 pt-6 border-t border-white/10 text-center text-xs text-white/40">
            © {{ date('Y') }} {{ $configuracoes->app_name }}. Todos os direitos reservados.
            <br>
            <span class="text-xs p-2">Feito com 🖤 por <a style="color:#fff;" target="_blank" href="{{env('DESENVOLVEDOR_URL')}}">{{env('DESENVOLVEDOR')}}</a></span>
        </div>
        
    </div>
</footer>