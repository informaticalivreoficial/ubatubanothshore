@php
    $whatsappNumber = $configuracoes->whatsapp_number;
    $whatsappNumber = $whatsappNumber
        ? (str_starts_with($whatsappNumber, '55') ? $whatsappNumber : '55'.$whatsappNumber)
        : null;
    $whatsappUrl = $whatsappNumber
        ? 'https://wa.me/'.$whatsappNumber.'?text='.urlencode('Olá! Preciso de ajuda com um documento do site.')
        : null;
@endphp

<section class="relative overflow-hidden bg-navy pb-24 pt-14 print:hidden">
    <div class="pointer-events-none absolute -right-24 -top-24 h-80 w-80 rounded-full bg-white/5 blur-3xl"></div>
    <div class="pointer-events-none absolute -bottom-32 left-1/4 h-72 w-72 rounded-full bg-accent/10 blur-3xl"></div>

    <div class="relative mx-auto max-w-7xl px-6">
        <nav class="mb-8 flex items-center gap-2 text-sm text-white/50" aria-label="Breadcrumb">
            <a href="{{ route('web.home') }}" class="transition-colors hover:text-white">Início</a>
            <span aria-hidden="true">/</span>
            <span class="text-white/80">{{ $title }}</span>
        </nav>

        <div class="max-w-3xl">
            <span class="inline-flex items-center gap-2 rounded-pill border border-white/15 bg-white/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-white/80">
                @if ($icon === 'shield')
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3l7 3v5c0 4.5-3 8.2-7 10-4-1.8-7-5.5-7-10V6l7-3z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4"/>
                    </svg>
                @else
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                @endif
                {{ $badge }}
            </span>

            <h1 class="mt-5 font-heading text-4xl font-bold leading-tight text-white sm:text-5xl">
                {{ $title }}
            </h1>

            <p class="mt-4 max-w-2xl text-base leading-relaxed text-white/70 sm:text-lg">
                {{ $description }}
            </p>

            <div class="mt-7 flex flex-wrap gap-3">
                @foreach ($features as $feature)
                    <span class="inline-flex items-center gap-2 rounded-pill bg-white/10 px-4 py-2 text-sm text-white/80">
                        <svg class="h-4 w-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        {{ $feature }}
                    </span>
                @endforeach
            </div>
        </div>
    </div>
</section>

<section class="relative z-10 -mt-14 pb-16 print:mt-0">
    <div class="mx-auto max-w-7xl px-6">
        <div class="grid gap-8 lg:grid-cols-12">
            <article class="lg:col-span-8">
                <div class="overflow-hidden rounded-card border border-gray-200 bg-white shadow-card">
                    <div class="flex flex-col gap-4 border-b border-gray-100 px-6 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-8">
                        <div class="flex items-center gap-3">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </span>

                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Documento institucional</p>
                                <p class="text-sm font-semibold text-navy">{{ $configuracoes->app_name }}</p>
                            </div>
                        </div>

                        <button
                            type="button"
                            onclick="window.print()"
                            class="inline-flex items-center justify-center gap-2 rounded-pill border border-gray-300 bg-white px-4 py-2 text-xs font-semibold text-gray-600 transition hover:border-navy hover:text-navy print:hidden"
                        >
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4H7v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                            </svg>
                            Imprimir
                        </button>
                    </div>

                    @if (count($sections))
                        <details class="group border-b border-gray-100 lg:hidden print:hidden">
                            <summary class="flex cursor-pointer list-none items-center justify-between gap-3 px-6 py-4 text-sm font-semibold text-navy sm:px-8">
                                <span class="flex items-center gap-2">
                                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6h16M4 12h10M4 18h7"/>
                                    </svg>
                                    Nesta página
                                </span>

                                <svg class="h-4 w-4 text-gray-400 transition-transform group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </summary>

                            <ol class="grid gap-1 px-4 pb-4 sm:grid-cols-2 sm:px-6">
                                @foreach ($sections as $section)
                                    <li>
                                        <a href="#{{ $section['id'] }}" class="flex items-start gap-2 rounded-lg px-2 py-1.5 text-sm text-gray-600 transition hover:bg-gray-50 hover:text-navy">
                                            <span class="mt-0.5 flex h-5 min-w-[1.25rem] items-center justify-center rounded bg-gray-100 px-1 text-[10px] font-bold text-gray-500">
                                                {{ $section['number'] }}
                                            </span>
                                            <span class="leading-snug">{{ $section['title'] }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ol>
                        </details>
                    @endif

                    <div class="legal-content px-6 py-8 sm:px-8">
                        {!! $content !!}
                    </div>

                    <div class="border-t border-gray-100 bg-gray-50 px-6 py-5 sm:px-8">
                        <p class="text-sm text-gray-500">
                            Precisa esclarecer algum ponto?
                            <a href="{{ route('web.contact') }}" class="font-semibold text-blue-600 transition-colors hover:text-blue-700">
                                Fale com a nossa equipe
                            </a>.
                        </p>
                    </div>
                </div>
            </article>

            <aside class="space-y-4 lg:col-span-4 print:hidden">
                <div class="sticky top-28 space-y-4">
                    @if (count($sections))
                        <nav class="hidden rounded-card border border-gray-200 bg-white p-5 shadow-sm lg:block" aria-label="Nesta página">
                            <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Nesta página</p>

                            <ol class="mt-3 max-h-[55vh] space-y-1 overflow-y-auto pr-1">
                                @foreach ($sections as $section)
                                    <li>
                                        <a href="#{{ $section['id'] }}" class="group flex items-start gap-2.5 rounded-lg px-2.5 py-2 text-sm text-gray-600 transition hover:bg-gray-50 hover:text-navy">
                                            <span class="mt-0.5 flex h-5 min-w-[1.25rem] items-center justify-center rounded bg-gray-100 px-1 text-[10px] font-bold text-gray-500 transition group-hover:bg-blue-50 group-hover:text-blue-600">
                                                {{ $section['number'] }}
                                            </span>
                                            <span class="leading-snug">{{ $section['title'] }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ol>
                        </nav>
                    @endif

                    <div class="rounded-card border border-gray-200 bg-white p-5 shadow-sm">
                        <div class="flex items-center gap-3">
                            <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-50 text-green-600">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.86 9.86 0 01-4.255-.949L3 20l1.395-3.72A7.96 7.96 0 013 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                            </span>

                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Dúvidas?</p>
                                <h2 class="font-heading text-base font-bold text-navy">Atendimento</h2>
                            </div>
                        </div>

                        <p class="mt-3 text-sm leading-relaxed text-gray-500">
                            Nossa equipe pode ajudar com informações sobre reservas, imóveis e seus dados.
                        </p>

                        <div class="mt-4 space-y-2">
                            @if ($whatsappUrl)
                                <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer" class="flex w-full items-center justify-center gap-2 rounded-lg bg-green-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-green-700">
                                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                                        <path d="M12.05 2C6.495 2 2 6.495 2 12.05c0 1.773.465 3.438 1.278 4.88L2 22l5.196-1.258A9.96 9.96 0 0012.05 22C17.605 22 22.1 17.505 22.1 11.95 22.1 6.495 17.605 2 12.05 2zm0 18.15c-1.62 0-3.13-.474-4.398-1.29l-.315-.187-3.083.746.78-3.005-.205-.326a7.95 7.95 0 01-1.22-4.238c0-4.4 3.58-7.98 7.98-7.98s7.98 3.58 7.98 7.98-3.58 7.98-7.98 7.98z"/>
                                    </svg>
                                    Falar no WhatsApp
                                </a>
                            @endif

                            <a href="{{ route('web.contact') }}" class="flex w-full items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:border-navy hover:text-navy">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                Enviar mensagem
                            </a>
                        </div>
                    </div>

                    <div class="rounded-card border border-gray-200 bg-white p-5 shadow-sm">
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Outro documento</p>

                        @if ($current === 'privacy')
                            <a href="{{ route('web.terms') }}" class="mt-2 flex items-center justify-between gap-3 text-sm font-semibold text-navy transition hover:text-blue-600">
                                Termos e Condições
                                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        @else
                            <a href="{{ route('web.privacy') }}" class="mt-2 flex items-center justify-between gap-3 text-sm font-semibold text-navy transition hover:text-blue-600">
                                Política de Privacidade
                                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        @endif
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>
