@extends('web.layouts.app')

@php
    $tagsList = $post->tags
        ? array_map('trim', explode(',', $post->tags))
        : [];

    $categoria = $post->categoryObject;
    $url = urlencode(request()->fullUrl());
    $title = urlencode($post->title);
@endphp

@section('content')
    <section class="relative overflow-hidden bg-navy pb-24 pt-14">
        <div class="pointer-events-none absolute -right-24 -top-24 h-80 w-80 rounded-full bg-white/5 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-32 left-1/4 h-72 w-72 rounded-full bg-accent/10 blur-3xl"></div>

        <div class="relative mx-auto max-w-7xl px-6">
            <nav class="mb-8 flex flex-wrap items-center gap-2 text-sm text-white/50" aria-label="Breadcrumb">
                <a href="{{ route('web.home') }}" class="transition-colors hover:text-white">Início</a>
                <span aria-hidden="true">/</span>
                <a href="{{ route('web.blog.index') }}" class="transition-colors hover:text-white">Blog</a>
                <span aria-hidden="true">/</span>
                <span class="text-white/80">{{ $post->title }}</span>
            </nav>

            <div class="max-w-4xl">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center gap-2 rounded-pill border border-white/15 bg-white/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-white/80">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H7a2 2 0 00-2 2v12a2 2 0 002 2h10"/>
                        </svg>
                        {{ $post->type === 'noticia' ? 'Notícia' : 'Artigo' }}
                    </span>

                    @if ($categoria)
                        <a href="{{ route('web.blog.category', $categoria->slug) }}" class="rounded-pill bg-accent px-4 py-2 text-xs font-bold uppercase tracking-wide text-navy transition hover:bg-accent/90">
                            {{ $categoria->title }}
                        </a>
                    @endif
                </div>

                <h1 class="mt-5 font-heading text-3xl font-bold leading-tight text-white sm:text-4xl lg:text-5xl">
                    {{ $post->title }}
                </h1>

                <p class="mt-4 max-w-3xl text-base leading-relaxed text-white/70 sm:text-lg">
                    {{ $post->summary(220) }}
                </p>

                <div class="mt-7 flex flex-wrap items-center gap-x-5 gap-y-3 text-sm text-white/60">
                    @if ($post->publish_at)
                        <span class="inline-flex items-center gap-2">
                            <svg class="h-4 w-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v13a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1z"/>
                            </svg>
                            {{ $post->publish_at }}
                        </span>
                    @endif

                    <span class="inline-flex items-center gap-2">
                        <svg class="h-4 w-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ max(1, (int) $post->readingTime) }} min de leitura
                    </span>

                    <span class="inline-flex items-center gap-2">
                        <svg class="h-4 w-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        {{ number_format($post->views, 0, ',', '.') }} visualizações
                    </span>
                </div>
            </div>
        </div>
    </section>

    <section class="relative z-10 -mt-12 pb-16">
        <div class="mx-auto max-w-7xl px-6">
            <div class="grid gap-8 lg:grid-cols-12">
                <article class="space-y-8 lg:col-span-8">
                    <div class="overflow-hidden rounded-card border border-gray-200 bg-white shadow-card">
                        <div class="relative aspect-[16/9] overflow-hidden bg-gray-100">
                            <img
                                src="{{ $post->cover() }}"
                                alt="{{ $post->title }}"
                                class="h-full w-full object-cover"
                            >
                        </div>

                        <div class="article-content px-6 py-8 sm:px-8 sm:py-10">
                            {!! $post->content !!}
                        </div>

                        @if (! empty($tagsList))
                            <div class="border-t border-gray-100 px-6 py-6 sm:px-8">
                                <h2 class="text-xs font-semibold uppercase tracking-wide text-gray-400">Tags</h2>
                                <div class="mt-3 flex flex-wrap gap-2">
                                    @foreach ($tagsList as $tag)
                                        <span class="rounded-pill bg-gray-100 px-3 py-1.5 text-xs font-medium text-gray-600">
                                            #{{ $tag }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="border-t border-gray-100 bg-gray-50 px-6 py-6 sm:px-8">
                            <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
                                <div>
                                    <h2 class="text-sm font-semibold text-navy">Gostou deste conteúdo?</h2>
                                    <p class="mt-1 text-xs text-gray-500">Compartilhe com quem também vai gostar.</p>
                                </div>

                                <div class="flex flex-wrap gap-2">
                                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ $url }}" target="_blank" rel="noopener noreferrer" aria-label="Compartilhar no Facebook" class="flex h-9 w-9 items-center justify-center rounded-lg bg-[#1877F2] text-white transition hover:scale-105">
                                        <svg class="h-4 w-4 fill-current" viewBox="0 0 24 24"><path d="M22 12a10 10 0 10-11.63 9.87v-6.99h-2.8V12h2.8V9.8c0-2.76 1.64-4.3 4.15-4.3 1.2 0 2.45.21 2.45.21v2.7h-1.38c-1.36 0-1.78.84-1.78 1.7V12h3.03l-.48 2.88h-2.55v6.99A10 10 0 0022 12z"/></svg>
                                    </a>

                                    <a href="https://twitter.com/intent/tweet?text={{ $title }}&url={{ $url }}" target="_blank" rel="noopener noreferrer" aria-label="Compartilhar no X" class="flex h-9 w-9 items-center justify-center rounded-lg bg-black text-white transition hover:scale-105">
                                        <svg class="h-4 w-4 fill-current" viewBox="0 0 24 24"><path d="M18.244 2H21l-6.563 7.502L22 22h-6.828l-5.341-7.03L3.463 22H1l7.02-8.02L2 2h6.91l4.825 6.37L18.244 2z"/></svg>
                                    </a>

                                    <a href="https://wa.me/?text={{ $title }}%20{{ $url }}" target="_blank" rel="noopener noreferrer" aria-label="Compartilhar no WhatsApp" class="flex h-9 w-9 items-center justify-center rounded-lg bg-[#25D366] text-white transition hover:scale-105">
                                        <svg class="h-4 w-4 fill-current" viewBox="0 0 24 24"><path d="M20.52 3.48A11.93 11.93 0 0012.04 0C5.43 0 .1 5.33.1 11.94c0 2.1.55 4.15 1.6 5.96L0 24l6.27-1.64a11.92 11.92 0 005.77 1.47h.01c6.61 0 11.94-5.33 11.94-11.94 0-3.19-1.24-6.19-3.47-8.41zM12.05 21.8h-.01a9.87 9.87 0 01-5.02-1.37l-.36-.21-3.72.97.99-3.63-.23-.37a9.87 9.87 0 01-1.51-5.24c0-5.46 4.45-9.9 9.91-9.9 2.65 0 5.14 1.03 7.01 2.91a9.84 9.84 0 012.89 7c0 5.46-4.45 9.9-9.91 9.9zm5.44-7.37c-.3-.15-1.76-.87-2.03-.97-.27-.1-.46-.15-.65.15-.2.3-.75.97-.92 1.17-.17.2-.34.22-.64.07-.3-.15-1.26-.46-2.4-1.46-.88-.79-1.48-1.76-1.66-2.06-.17-.3-.02-.46.13-.61.13-.13.3-.34.45-.5.15-.17.2-.3.3-.5.1-.2.05-.37-.02-.52-.07-.15-.65-1.57-.9-2.15-.24-.57-.49-.5-.65-.51h-.55c-.2 0-.52.07-.8.37-.27.3-1.04 1.02-1.04 2.48 0 1.46 1.06 2.87 1.21 3.07.15.2 2.08 3.17 5.05 4.45.7.3 1.25.48 1.68.62.7.22 1.33.19 1.83.12.56-.08 1.76-.72 2.01-1.41.25-.7.25-1.3.17-1.41-.07-.1-.27-.17-.57-.32z"/></svg>
                                    </a>

                                    <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ $url }}" target="_blank" rel="noopener noreferrer" aria-label="Compartilhar no LinkedIn" class="flex h-9 w-9 items-center justify-center rounded-lg bg-[#0A66C2] text-white transition hover:scale-105">
                                        <svg class="h-4 w-4 fill-current" viewBox="0 0 24 24"><path d="M4.98 3.5C4.98 4.88 3.86 6 2.49 6S0 4.88 0 3.5 1.12 1 2.49 1s2.49 1.12 2.49 2.5zM0 8h5v16H0V8zm7.5 0h4.78v2.16h.07c.67-1.27 2.3-2.6 4.73-2.6 5.06 0 6 3.33 6 7.66V24h-5v-7.84c0-1.87-.03-4.28-2.61-4.28-2.61 0-3.01 2.04-3.01 4.15V24h-5V8z"/></svg>
                                    </a>

                                    <a href="https://t.me/share/url?url={{ $url }}&text={{ $title }}" target="_blank" rel="noopener noreferrer" aria-label="Compartilhar no Telegram" class="flex h-9 w-9 items-center justify-center rounded-lg bg-[#229ED9] text-white transition hover:scale-105">
                                        <svg class="h-4 w-4 fill-current" viewBox="0 0 24 24"><path d="M9.993 15.674l-.4 4.326c.573 0 .822-.246 1.123-.54l2.694-2.577 5.586 4.09c1.024.563 1.75.267 2.004-.95l3.63-17.037c.337-1.56-.563-2.17-1.558-1.8L1.357 9.63c-1.516.592-1.495 1.44-.258 1.823l5.66 1.77 13.148-8.29c.62-.41 1.184-.183.72.227"/></svg>
                                    </a>

                                    <a href="mailto:?subject={{ $title }}&body={{ $url }}" aria-label="Compartilhar por e-mail" class="flex h-9 w-9 items-center justify-center rounded-lg bg-gray-600 text-white transition hover:scale-105">
                                        <svg class="h-4 w-4 fill-current" viewBox="0 0 24 24"><path d="M12 13.065L.015 4.5A2 2 0 012 3h20a2 2 0 011.985 1.5L12 13.065zM0 6.697V19a2 2 0 002 2h20a2 2 0 002-2V6.697l-12 8.25L0 6.697z"/></svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if ($post->images()->count())
                        <div class="rounded-card border border-gray-200 bg-white p-6 shadow-sm sm:p-8">
                            <h2 class="font-heading text-xl font-bold text-navy">Galeria</h2>

                            <div class="mt-5 grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4">
                                @foreach ($post->images as $image)
                                    <a href="{{ $image->url_image }}" data-fancybox="gallery" data-caption="{{ $post->title }}" class="group block overflow-hidden rounded-xl">
                                        <img
                                            src="{{ $image->url_cropped }}"
                                            alt="{{ $post->title }}"
                                            loading="lazy"
                                            class="h-32 w-full object-cover transition duration-300 group-hover:scale-105"
                                        >
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if ($relacionados->count())
                        <div>
                            <div class="flex items-end justify-between gap-4">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-blue-600">Continue explorando</p>
                                    <h2 class="mt-2 font-heading text-2xl font-bold text-navy">Posts relacionados</h2>
                                </div>

                                <a href="{{ route('web.blog.index') }}" class="hidden text-sm font-semibold text-navy transition hover:text-blue-600 sm:inline-flex">
                                    Ver todos
                                </a>
                            </div>

                            <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                                @foreach ($relacionados as $rel)
                                    @include('web.components.blog-card', ['post' => $rel])
                                @endforeach
                            </div>
                        </div>
                    @endif
                </article>

                <aside class="space-y-4 lg:col-span-4">
                    <div class="sticky top-28 space-y-4">
                        @if ($categorias->count())
                            <div class="rounded-card border border-gray-200 bg-white p-5 shadow-sm">
                                <h2 class="font-heading text-base font-bold text-navy">Categorias</h2>

                                <ul class="mt-3 space-y-1">
                                    @foreach ($categorias as $cat)
                                        <li>
                                            <a href="{{ route('web.blog.category', $cat->slug) }}" class="flex items-center justify-between gap-3 rounded-lg px-2.5 py-2 text-sm transition {{ $categoria && $categoria->id === $cat->id ? 'bg-blue-50 font-semibold text-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-navy' }}">
                                                <span>{{ $cat->title }}</span>
                                                <span class="rounded-pill bg-gray-100 px-2 py-0.5 text-[10px] font-bold text-gray-500">
                                                    {{ $cat->posts_count }}
                                                </span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if ($postsMais->count())
                            <div class="rounded-card border border-gray-200 bg-white p-5 shadow-sm">
                                <h2 class="font-heading text-base font-bold text-navy">Mais visitados</h2>

                                <ul class="mt-4 space-y-4">
                                    @foreach ($postsMais as $i => $visitado)
                                        <li>
                                            <a href="{{ $visitado->url }}" class="group flex gap-3">
                                                <span class="font-heading text-2xl font-bold leading-none text-gray-200 transition group-hover:text-accent">
                                                    {{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}
                                                </span>

                                                <span class="min-w-0">
                                                    <span class="block text-sm font-semibold leading-snug text-gray-700 transition group-hover:text-blue-600 line-clamp-2">
                                                        {{ $visitado->title }}
                                                    </span>
                                                    <span class="mt-1 block text-xs text-gray-400">
                                                        {{ number_format($visitado->views, 0, ',', '.') }} visualizações
                                                    </span>
                                                </span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if ($todasTags->count())
                            <div class="rounded-card border border-gray-200 bg-white p-5 shadow-sm">
                                <h2 class="font-heading text-base font-bold text-navy">Tags populares</h2>

                                <div class="mt-3 flex flex-wrap gap-2">
                                    @foreach ($todasTags as $tag)
                                        <span class="rounded-pill bg-gray-100 px-3 py-1.5 text-xs font-medium text-gray-600">
                                            #{{ $tag }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="overflow-hidden rounded-card bg-navy p-6 text-white shadow-card">
                            <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-white/10 text-accent">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                            </span>

                            <h2 class="mt-4 font-heading text-lg font-bold">Encontre seu imóvel</h2>
                            <p class="mt-2 text-sm leading-relaxed text-white/65">
                                Conheça opções de temporada em Ubatuba para a sua próxima viagem.
                            </p>

                            <a href="{{ route('web.properties') }}" class="mt-4 inline-flex items-center gap-2 rounded-pill bg-white px-5 py-2.5 text-sm font-bold text-navy transition hover:bg-accent">
                                Ver imóveis
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5/dist/fancybox/fancybox.css"/>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5/dist/fancybox/fancybox.umd.js"></script>
    <script>
        Fancybox.bind("[data-fancybox='gallery']", {
            Thumbs: { autoStart: true },
        });
    </script>
@endpush
