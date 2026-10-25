@php
    $footerWhatsapp = $configuracoes->whatsapp_number;
    $footerWhatsapp = $footerWhatsapp
        ? (str_starts_with($footerWhatsapp, '55') ? $footerWhatsapp : '55'.$footerWhatsapp)
        : null;

    $footerAddress = array_filter([
        $configuracoes->street,
        $configuracoes->number,
        $configuracoes->neighborhood,
        $configuracoes->city,
        $configuracoes->state,
    ]);

    $footerAddress = implode(', ', $footerAddress);
    $footerMapsUrl = $footerAddress
        ? 'https://www.google.com/maps/search/?api=1&query='.urlencode($footerAddress)
        : null;
@endphp

<footer class="relative overflow-hidden bg-[#051e34] text-white">
    <div class="h-1 bg-gradient-to-r from-accent via-accent/40 to-transparent"></div>
    <div class="pointer-events-none absolute -right-24 top-10 h-72 w-72 rounded-full bg-white/[0.03] blur-3xl"></div>

    <div class="relative mx-auto max-w-7xl px-6 pb-8 pt-14">
        <div class="grid gap-10 sm:grid-cols-2 lg:grid-cols-12">
            <div class="sm:col-span-2 lg:col-span-4">
                <a href="{{ route('web.home') }}" class="inline-flex">
                    <img
                        src="{{ $configuracoes->getlogofooter() }}"
                        alt="{{ $configuracoes->app_name }}"
                        class="h-16 w-auto object-contain"
                    >
                </a>

                <p class="mt-5 max-w-sm text-sm leading-relaxed text-white/55">
                    Imóveis para locação por temporada em Ubatuba/SP. Conforto, localização e atendimento
                    próximo para você aproveitar o litoral norte com tranquilidade.
                </p>

                <div class="mt-6 flex items-center gap-3">
                    @if ($configuracoes->instagram)
                        <a href="{{ $configuracoes->instagram }}" target="_blank" rel="noopener noreferrer" aria-label="Instagram" class="flex h-10 w-10 items-center justify-center rounded-lg bg-white/5 text-white/60 transition hover:bg-white/10 hover:text-white">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <rect x="3" y="3" width="18" height="18" rx="5" stroke-width="1.8"/>
                                <circle cx="12" cy="12" r="4" stroke-width="1.8"/>
                                <circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/>
                            </svg>
                        </a>
                    @endif

                    @if ($configuracoes->facebook)
                        <a href="{{ $configuracoes->facebook }}" target="_blank" rel="noopener noreferrer" aria-label="Facebook" class="flex h-10 w-10 items-center justify-center rounded-lg bg-white/5 text-white/60 transition hover:bg-white/10 hover:text-white">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M22 12.06C22 6.5 17.52 2 12 2S2 6.5 2 12.06c0 5.02 3.66 9.18 8.44 9.94v-7.03H7.9v-2.91h2.54V9.85c0-2.52 1.49-3.91 3.77-3.91 1.09 0 2.24.2 2.24.2v2.47h-1.26c-1.24 0-1.63.78-1.63 1.57v1.88h2.78l-.44 2.91h-2.34V22c4.78-.76 8.44-4.92 8.44-9.94z"/>
                            </svg>
                        </a>
                    @endif

                    @if ($configuracoes->youtube)
                        <a href="{{ $configuracoes->youtube }}" target="_blank" rel="noopener noreferrer" aria-label="YouTube" class="flex h-10 w-10 items-center justify-center rounded-lg bg-white/5 text-white/60 transition hover:bg-white/10 hover:text-white">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.5 6.2a3.02 3.02 0 00-2.12-2.14C19.5 3.56 12 3.56 12 3.56s-7.5 0-9.38.5A3.02 3.02 0 00.5 6.2 31.5 31.5 0 000 12a31.5 31.5 0 00.5 5.8 3.02 3.02 0 002.12 2.14c1.88.5 9.38.5 9.38.5s7.5 0 9.38-.5a3.02 3.02 0 002.12-2.14A31.5 31.5 0 0024 12a31.5 31.5 0 00-.5-5.8zM9.55 15.57V8.43L15.82 12l-6.27 3.57z"/>
                            </svg>
                        </a>
                    @endif

                    @if ($configuracoes->linkedin)
                        <a href="{{ $configuracoes->linkedin }}" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn" class="flex h-10 w-10 items-center justify-center rounded-lg bg-white/5 text-white/60 transition hover:bg-white/10 hover:text-white">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M4.98 3.5C4.98 4.88 3.87 6 2.5 6S0 4.88 0 3.5 1.12 1 2.5 1s2.48 1.12 2.48 2.5zM.5 8h4V22h-4V8zm7.5 0h3.83v1.91h.05c.53-1 1.84-2.06 3.79-2.06 4.05 0 4.8 2.67 4.8 6.14V22h-4v-6.9c0-1.65-.03-3.77-2.3-3.77-2.3 0-2.65 1.8-2.65 3.65V22h-4V8z"/>
                            </svg>
                        </a>
                    @endif
                </div>
            </div>

            <div class="lg:col-span-2">
                <h3 class="font-heading text-sm font-bold text-white">Navegação</h3>

                <ul class="mt-4 space-y-2.5 text-sm">
                    <li><a href="{{ route('web.home') }}" class="text-white/55 transition-colors hover:text-accent">Início</a></li>
                    <li><a href="{{ route('web.properties') }}" class="text-white/55 transition-colors hover:text-accent">Todos os imóveis</a></li>
                    <li><a href="{{ route('web.blog.index') }}" class="text-white/55 transition-colors hover:text-accent">Blog</a></li>
                    <li><a href="{{ route('web.contact') }}" class="text-white/55 transition-colors hover:text-accent">Atendimento</a></li>
                </ul>
            </div>

            <div class="lg:col-span-3">
                <h3 class="font-heading text-sm font-bold text-white">Institucional</h3>

                <ul class="mt-4 space-y-2.5 text-sm">
                    <li><a href="{{ route('web.privacy') }}" class="text-white/55 transition-colors hover:text-accent">Política de Privacidade</a></li>
                    <li><a href="{{ route('web.terms') }}" class="text-white/55 transition-colors hover:text-accent">Termos e Condições</a></li>
                    <li>
                        <button type="button" @click="openModal()" class="text-left text-white/55 transition-colors hover:text-accent">
                            Preferências de cookies
                        </button>
                    </li>
                </ul>
            </div>

            <div class="lg:col-span-3">
                <h3 class="font-heading text-sm font-bold text-white">Contato</h3>

                <ul class="mt-4 space-y-3 text-sm">
                    @if ($footerWhatsapp)
                        <li>
                            <a href="https://wa.me/{{ $footerWhatsapp }}" target="_blank" rel="noopener noreferrer" class="flex items-start gap-3 text-white/55 transition-colors hover:text-accent">
                                <svg class="mt-0.5 h-4 w-4 shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                                    <path d="M12.05 2C6.495 2 2 6.495 2 12.05c0 1.773.465 3.438 1.278 4.88L2 22l5.196-1.258A9.96 9.96 0 0012.05 22C17.605 22 22.1 17.505 22.1 11.95 22.1 6.495 17.605 2 12.05 2zm0 18.15c-1.62 0-3.13-.474-4.398-1.29l-.315-.187-3.083.746.78-3.005-.205-.326a7.95 7.95 0 01-1.22-4.238c0-4.4 3.58-7.98 7.98-7.98s7.98 3.58 7.98 7.98-3.58 7.98-7.98 7.98z"/>
                                </svg>
                                <span>WhatsApp<br><span class="text-white/80">{{ $configuracoes->whatsapp }}</span></span>
                            </a>
                        </li>
                    @endif

                    @if ($configuracoes->phone || $configuracoes->cell_phone)
                        <li>
                            @php($footerPhone = $configuracoes->phone ?: $configuracoes->cell_phone)
                            <a href="tel:{{ preg_replace('/\D/', '', $footerPhone) }}" class="flex items-start gap-3 text-white/55 transition-colors hover:text-accent">
                                <svg class="mt-0.5 h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 5a2 2 0 012-2h2.28a1 1 0 01.95.68l1.2 3.6a1 1 0 01-.26 1.05l-1.5 1.5a14.05 14.05 0 005.5 5.5l1.5-1.5a1 1 0 011.05-.26l3.6 1.2a1 1 0 01.68.95V19a2 2 0 01-2 2h-1C9.16 21 3 14.84 3 7V5z"/>
                                </svg>
                                <span>{{ $footerPhone }}</span>
                            </a>
                        </li>
                    @endif

                    @if ($configuracoes->email)
                        <li>
                            <a href="mailto:{{ $configuracoes->email }}" class="flex items-start gap-3 break-words text-white/55 transition-colors hover:text-accent">
                                <svg class="mt-0.5 h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <span>{{ $configuracoes->email }}</span>
                            </a>
                        </li>
                    @endif

                    @if ($configuracoes->display_address && $footerAddress)
                        <li>
                            @if ($footerMapsUrl)
                                <a href="{{ $footerMapsUrl }}" target="_blank" rel="noopener noreferrer" class="flex items-start gap-3 text-white/55 transition-colors hover:text-accent">
                            @else
                                <span class="flex items-start gap-3 text-white/55">
                            @endif

                                <svg class="mt-0.5 h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span>{{ $footerAddress }}</span>

                            @if ($footerMapsUrl)
                                </a>
                            @else
                                </span>
                            @endif
                        </li>
                    @endif
                </ul>
            </div>
        </div>

        <div class="mt-12 flex flex-col items-center justify-between gap-3 border-t border-white/10 pt-6 text-center sm:flex-row sm:text-left">
            <p class="text-xs text-white/40">
                © {{ date('Y') }} {{ $configuracoes->app_name }}. Todos os direitos reservados.
            </p>

            <p class="text-xs text-white/40">
                Desenvolvido por
                <a href="{{ config('app.desenvolvedor_url') }}" target="_blank" rel="noopener noreferrer" class="text-white/70 transition-colors hover:text-accent">
                    {{ config('app.desenvolvedor_nome') }}
                </a>
            </p>
        </div>
    </div>
</footer>
