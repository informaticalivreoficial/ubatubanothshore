@extends("web.layouts.app")

@section('content')
    @php
        $whatsappNumber = $configuracoes->whatsapp_number;
        $whatsappNumber = $whatsappNumber
            ? (str_starts_with($whatsappNumber, '55') ? $whatsappNumber : '55'.$whatsappNumber)
            : null;
        $whatsappUrl = $whatsappNumber
            ? 'https://wa.me/'.$whatsappNumber.'?text='.urlencode('Olá! Vim pelo site e gostaria de atendimento.')
            : null;

        $addressParts = array_filter([
            $configuracoes->street,
            $configuracoes->number,
            $configuracoes->neighborhood,
            $configuracoes->city,
            $configuracoes->state,
        ]);

        $fullAddress = implode(', ', $addressParts);
        $mapsUrl = $fullAddress
            ? 'https://www.google.com/maps/search/?api=1&query='.urlencode($fullAddress)
            : null;
    @endphp

    <section class="relative overflow-hidden bg-navy pb-28 pt-14">
        <div class="pointer-events-none absolute -right-24 -top-24 h-80 w-80 rounded-full bg-white/5 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-32 left-1/4 h-72 w-72 rounded-full bg-accent/10 blur-3xl"></div>

        <div class="relative mx-auto max-w-7xl px-6">
            <nav class="mb-8 flex items-center gap-2 text-sm text-white/50" aria-label="Breadcrumb">
                <a href="{{ route('web.home') }}" class="transition-colors hover:text-white">Início</a>
                <span aria-hidden="true">/</span>
                <span class="text-white/80">Atendimento</span>
            </nav>

            <div class="max-w-3xl">
                <span class="inline-flex items-center gap-2 rounded-pill border border-white/15 bg-white/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-white/80">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.86 9.86 0 01-4.255-.949L3 20l1.395-3.72A7.96 7.96 0 013 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                    Atendimento
                </span>

                <h1 class="mt-5 font-heading text-4xl font-bold leading-tight text-white sm:text-5xl">
                    Estamos prontos para ajudar
                </h1>

                <p class="mt-4 max-w-2xl text-base leading-relaxed text-white/70 sm:text-lg">
                    Tire dúvidas sobre imóveis, disponibilidade, valores e reservas. Nossa equipe retorna
                    o contato pelos canais informados.
                </p>

                <div class="mt-7 flex flex-wrap gap-3">
                    <span class="inline-flex items-center gap-2 rounded-pill bg-white/10 px-4 py-2 text-sm text-white/80">
                        <svg class="h-4 w-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Atendimento humano
                    </span>

                    <span class="inline-flex items-center gap-2 rounded-pill bg-white/10 px-4 py-2 text-sm text-white/80">
                        <svg class="h-4 w-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Equipe em Ubatuba
                    </span>

                    <span class="inline-flex items-center gap-2 rounded-pill bg-white/10 px-4 py-2 text-sm text-white/80">
                        <svg class="h-4 w-4 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        WhatsApp, telefone e e-mail
                    </span>
                </div>
            </div>
        </div>
    </section>

    <section class="relative z-10 -mt-16 pb-16">
        <div class="mx-auto max-w-7xl px-6">
            <div class="grid gap-8 lg:grid-cols-5">
                <div class="lg:col-span-3">
                    <div class="rounded-card border border-gray-200 bg-white p-6 shadow-card sm:p-8">
                        <div class="flex items-start gap-4 border-b border-gray-100 pb-6">
                            <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.86 9.86 0 01-4.255-.949L3 20l1.395-3.72A7.96 7.96 0 013 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                            </span>

                            <div>
                                <h2 class="font-heading text-xl font-bold text-navy">Envie sua mensagem</h2>
                                <p class="mt-1 text-sm leading-relaxed text-gray-500">
                                    Preencha os dados abaixo. Quanto mais detalhes, melhor poderemos ajudar.
                                </p>
                            </div>
                        </div>

                        <div class="mt-6">
                            <livewire:web.contact-form />
                        </div>
                    </div>
                </div>

                <aside class="space-y-4 lg:col-span-2">
                    <div class="rounded-card border border-gray-200 bg-white p-5 shadow-sm">
                        <div class="flex items-center gap-3">
                            <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-navy text-white">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 5a2 2 0 012-2h2.28a1 1 0 01.95.68l1.2 3.6a1 1 0 01-.26 1.05l-1.5 1.5a14.05 14.05 0 005.5 5.5l1.5-1.5a1 1 0 011.05-.26l3.6 1.2a1 1 0 01.68.95V19a2 2 0 01-2 2h-1C9.16 21 3 14.84 3 7V5z"/>
                                </svg>
                            </span>
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Canais de atendimento</p>
                                <h2 class="font-heading text-lg font-bold text-navy">Fale com a nossa equipe</h2>
                            </div>
                        </div>
                    </div>

                    @if ($whatsappUrl)
                        <a
                            href="{{ $whatsappUrl }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="group flex items-start gap-4 rounded-card border border-gray-200 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:border-green-200 hover:shadow-card"
                        >
                            <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-green-50 text-green-600">
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                                    <path d="M12.05 2C6.495 2 2 6.495 2 12.05c0 1.773.465 3.438 1.278 4.88L2 22l5.196-1.258A9.96 9.96 0 0012.05 22C17.605 22 22.1 17.505 22.1 11.95 22.1 6.495 17.605 2 12.05 2zm0 18.15c-1.62 0-3.13-.474-4.398-1.29l-.315-.187-3.083.746.78-3.005-.205-.326a7.95 7.95 0 01-1.22-4.238c0-4.4 3.58-7.98 7.98-7.98s7.98 3.58 7.98 7.98-3.58 7.98-7.98 7.98z"/>
                                </svg>
                            </span>

                            <span class="min-w-0">
                                <span class="block text-xs font-semibold uppercase tracking-wide text-gray-400">WhatsApp</span>
                                <span class="mt-1 block font-heading text-base font-bold text-navy">{{ $configuracoes->whatsapp }}</span>
                                <span class="mt-1 block text-sm text-gray-500">Clique para iniciar uma conversa</span>
                            </span>

                            <svg class="ml-auto mt-1 h-5 w-5 shrink-0 text-gray-300 transition group-hover:text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    @endif

                    @if ($configuracoes->phone || $configuracoes->cell_phone)
                        <div class="rounded-card border border-gray-200 bg-white p-5 shadow-sm">
                            <div class="flex items-start gap-4">
                                <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 5a2 2 0 012-2h2.28a1 1 0 01.95.68l1.2 3.6a1 1 0 01-.26 1.05l-1.5 1.5a14.05 14.05 0 005.5 5.5l1.5-1.5a1 1 0 011.05-.26l3.6 1.2a1 1 0 01.68.95V19a2 2 0 01-2 2h-1C9.16 21 3 14.84 3 7V5z"/>
                                    </svg>
                                </span>

                                <div class="min-w-0">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Telefone</p>

                                    @if ($configuracoes->phone)
                                        <a href="tel:{{ preg_replace('/\D/', '', $configuracoes->phone) }}" class="mt-1 block font-heading text-base font-bold text-navy transition-colors hover:text-blue-600">
                                            {{ $configuracoes->phone }}
                                        </a>
                                    @endif

                                    @if ($configuracoes->cell_phone)
                                        <a href="tel:{{ preg_replace('/\D/', '', $configuracoes->cell_phone) }}" class="mt-1 block font-heading text-base font-bold text-navy transition-colors hover:text-blue-600">
                                            {{ $configuracoes->cell_phone }}
                                        </a>
                                    @endif

                                    <p class="mt-1 text-sm text-gray-500">Ligue ou envie uma mensagem</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($configuracoes->email)
                        <div class="rounded-card border border-gray-200 bg-white p-5 shadow-sm">
                            <div class="flex items-start gap-4">
                                <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </span>

                                <div class="min-w-0">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">E-mail</p>

                                    <a href="mailto:{{ $configuracoes->email }}" class="mt-1 block break-words font-heading text-sm font-bold text-navy transition-colors hover:text-indigo-600 sm:text-base">
                                        {{ $configuracoes->email }}
                                    </a>

                                    @if ($configuracoes->additional_email)
                                        <a href="mailto:{{ $configuracoes->additional_email }}" class="mt-1 block break-words text-sm text-gray-500 transition-colors hover:text-indigo-600">
                                            {{ $configuracoes->additional_email }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($configuracoes->display_address && $fullAddress)
                        <div class="rounded-card border border-gray-200 bg-white p-5 shadow-sm">
                            <div class="flex items-start gap-4">
                                <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </span>

                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Escritório</p>
                                    <p class="mt-1 text-sm leading-relaxed text-gray-600">{{ $fullAddress }}</p>

                                    @if ($mapsUrl)
                                        <a href="{{ $mapsUrl }}" target="_blank" rel="noopener noreferrer" class="mt-2 inline-flex items-center gap-1 text-sm font-semibold text-amber-600 transition-colors hover:text-amber-700">
                                            Ver no mapa
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                            </svg>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($configuracoes->facebook || $configuracoes->instagram || $configuracoes->youtube || $configuracoes->linkedin)
                        <div class="rounded-card border border-gray-200 bg-white p-5 shadow-sm">
                            <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Acompanhe</p>

                            <div class="mt-3 flex flex-wrap gap-2">
                                @if ($configuracoes->instagram)
                                    <a href="{{ $configuracoes->instagram }}" target="_blank" rel="noopener noreferrer" aria-label="Instagram" class="flex h-10 w-10 items-center justify-center rounded-lg bg-gray-50 text-gray-500 transition hover:bg-pink-50 hover:text-pink-600">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <rect x="3" y="3" width="18" height="18" rx="5" stroke-width="1.8"/>
                                            <circle cx="12" cy="12" r="4" stroke-width="1.8"/>
                                            <circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/>
                                        </svg>
                                    </a>
                                @endif

                                @if ($configuracoes->facebook)
                                    <a href="{{ $configuracoes->facebook }}" target="_blank" rel="noopener noreferrer" aria-label="Facebook" class="flex h-10 w-10 items-center justify-center rounded-lg bg-gray-50 text-gray-500 transition hover:bg-blue-50 hover:text-blue-600">
                                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M22 12.06C22 6.5 17.52 2 12 2S2 6.5 2 12.06c0 5.02 3.66 9.18 8.44 9.94v-7.03H7.9v-2.91h2.54V9.85c0-2.52 1.49-3.91 3.77-3.91 1.09 0 2.24.2 2.24.2v2.47h-1.26c-1.24 0-1.63.78-1.63 1.57v1.88h2.78l-.44 2.91h-2.34V22c4.78-.76 8.44-4.92 8.44-9.94z"/>
                                        </svg>
                                    </a>
                                @endif

                                @if ($configuracoes->youtube)
                                    <a href="{{ $configuracoes->youtube }}" target="_blank" rel="noopener noreferrer" aria-label="YouTube" class="flex h-10 w-10 items-center justify-center rounded-lg bg-gray-50 text-gray-500 transition hover:bg-red-50 hover:text-red-600">
                                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M23.5 6.2a3.02 3.02 0 00-2.12-2.14C19.5 3.56 12 3.56 12 3.56s-7.5 0-9.38.5A3.02 3.02 0 00.5 6.2 31.5 31.5 0 000 12a31.5 31.5 0 00.5 5.8 3.02 3.02 0 002.12 2.14c1.88.5 9.38.5 9.38.5s7.5 0 9.38-.5a3.02 3.02 0 002.12-2.14A31.5 31.5 0 0024 12a31.5 31.5 0 00-.5-5.8zM9.55 15.57V8.43L15.82 12l-6.27 3.57z"/>
                                        </svg>
                                    </a>
                                @endif

                                @if ($configuracoes->linkedin)
                                    <a href="{{ $configuracoes->linkedin }}" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn" class="flex h-10 w-10 items-center justify-center rounded-lg bg-gray-50 text-gray-500 transition hover:bg-sky-50 hover:text-sky-700">
                                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M4.98 3.5C4.98 4.88 3.87 6 2.5 6S0 4.88 0 3.5 1.12 1 2.5 1s2.48 1.12 2.48 2.5zM.5 8h4V22h-4V8zm7.5 0h3.83v1.91h.05c.53-1 1.84-2.06 3.79-2.06 4.05 0 4.8 2.67 4.8 6.14V22h-4v-6.9c0-1.65-.03-3.77-2.3-3.77-2.3 0-2.65 1.8-2.65 3.65V22h-4V8z"/>
                                        </svg>
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif
                </aside>
            </div>
        </div>
    </section>
@endsection
