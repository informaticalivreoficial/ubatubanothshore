@extends('web.layouts.app')

@section('content')
    @php
        $categoriaAtual = $category ?? null;
    @endphp

    <section class="relative overflow-hidden bg-navy pb-24 pt-14">
        <div class="pointer-events-none absolute -right-24 -top-24 h-80 w-80 rounded-full bg-white/5 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-32 left-1/4 h-72 w-72 rounded-full bg-accent/10 blur-3xl"></div>

        <div class="relative mx-auto max-w-7xl px-6">
            <nav class="mb-8 flex flex-wrap items-center gap-2 text-sm text-white/50" aria-label="Breadcrumb">
                <a href="{{ route('web.home') }}" class="transition-colors hover:text-white">Início</a>
                <span aria-hidden="true">/</span>

                @if ($categoriaAtual)
                    <a href="{{ route('web.blog.index') }}" class="transition-colors hover:text-white">Blog</a>
                    <span aria-hidden="true">/</span>
                    <span class="text-white/80">{{ $categoriaAtual->title }}</span>
                @else
                    <span class="text-white/80">Blog</span>
                @endif
            </nav>

            <div class="max-w-3xl">
                <span class="inline-flex items-center gap-2 rounded-pill border border-white/15 bg-white/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-white/80">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H7a2 2 0 00-2 2v12a2 2 0 002 2h10"/>
                    </svg>
                    {{ $categoriaAtual ? 'Categoria' : 'Blog' }}
                </span>

                <h1 class="mt-5 font-heading text-4xl font-bold leading-tight text-white sm:text-5xl">
                    {{ $categoriaAtual ? $categoriaAtual->title : 'Histórias, dicas e novidades' }}
                </h1>

                <p class="mt-4 max-w-2xl text-base leading-relaxed text-white/70 sm:text-lg">
                    @if ($categoriaAtual && $categoriaAtual->content)
                        {{ $categoriaAtual->content }}
                    @else
                        Descubra conteúdos sobre Ubatuba, hospedagem e tudo o que envolve aproveitar o litoral norte.
                    @endif
                </p>

                <div class="mt-7 flex flex-wrap items-center gap-3">
                    <span class="inline-flex items-center gap-2 rounded-pill bg-white/10 px-4 py-2 text-sm text-white/80">
                        <svg class="h-4 w-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        {{ $posts->total() }} {{ $posts->total() === 1 ? 'publicação' : 'publicações' }}
                    </span>

                    @if ($categorias->count())
                        <span class="inline-flex items-center gap-2 rounded-pill bg-white/10 px-4 py-2 text-sm text-white/80">
                            <svg class="h-4 w-4 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 7h.01M7 3h5a2 2 0 011.414.586l7 7a2 2 0 010 2.828l-5 5a2 2 0 01-2.828 0l-7-7A2 2 0 013 10V5a2 2 0 012-2h2z"/>
                            </svg>
                            {{ $categorias->count() }} {{ $categorias->count() === 1 ? 'categoria' : 'categorias' }}
                        </span>
                    @endif
                </div>

                @if ($categorias->count())
                    <div class="mt-7 flex flex-wrap gap-2">
                        <a href="{{ route('web.blog.index') }}" class="rounded-pill px-4 py-2 text-sm font-semibold transition {{ ! $categoriaAtual ? 'bg-white text-navy' : 'bg-white/10 text-white/75 hover:bg-white/15 hover:text-white' }}">
                            Todos
                        </a>

                        @foreach ($categorias as $cat)
                            <a href="{{ route('web.blog.category', $cat->slug) }}" class="rounded-pill px-4 py-2 text-sm font-semibold transition {{ $categoriaAtual && $categoriaAtual->id === $cat->id ? 'bg-white text-navy' : 'bg-white/10 text-white/75 hover:bg-white/15 hover:text-white' }}">
                                {{ $cat->title }}
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </section>

    <section class="relative z-10 -mt-12 pb-16">
        <div class="mx-auto max-w-7xl px-6">
            @if ($posts->count())
                @php
                    $featured = $posts->first();
                    $featuredCategory = $featured->categoryObject;
                @endphp

                <article class="grid overflow-hidden rounded-card border border-gray-200 bg-white shadow-card lg:grid-cols-2">
                    <a href="{{ $featured->url }}" class="group relative block min-h-[280px] overflow-hidden bg-gray-100 lg:min-h-[420px]">
                        <img
                            src="{{ $featured->cover() }}"
                            alt="{{ $featured->title }}"
                            class="h-full w-full object-cover transition duration-700 group-hover:scale-105"
                        >

                        <span class="absolute left-5 top-5 inline-flex items-center gap-2 rounded-pill bg-white/95 px-4 py-2 text-[11px] font-bold uppercase tracking-wide text-navy shadow-sm backdrop-blur-sm">
                            <svg class="h-3.5 w-3.5 text-accent" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2l2.9 6.26L22 9.27l-5 4.87L18.18 22 12 18.56 5.82 22 7 14.14l-5-4.87 7.1-1.01L12 2z"/>
                            </svg>
                            Em destaque
                        </span>
                    </a>

                    <div class="flex flex-col p-6 sm:p-8 lg:p-10">
                        <div class="flex flex-wrap items-center gap-2 text-xs text-gray-400">
                            @if ($featuredCategory)
                                <span class="rounded-pill bg-blue-50 px-3 py-1 text-[11px] font-bold uppercase tracking-wide text-blue-600">
                                    {{ $featuredCategory->title }}
                                </span>
                            @endif

                            @if ($featured->publish_at)
                                <span class="inline-flex items-center gap-1.5">
                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v13a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1z"/>
                                    </svg>
                                    {{ $featured->publish_at }}
                                </span>
                            @endif

                            <span class="h-1 w-1 rounded-full bg-gray-300" aria-hidden="true"></span>
                            <span>{{ max(1, (int) $featured->readingTime) }} min de leitura</span>
                        </div>

                        <h2 class="mt-4 font-heading text-2xl font-bold leading-tight text-navy sm:text-3xl">
                            <a href="{{ $featured->url }}" class="transition-colors hover:text-blue-600">
                                {{ $featured->title }}
                            </a>
                        </h2>

                        <p class="mt-4 text-base leading-relaxed text-gray-500 line-clamp-4">
                            {{ $featured->summary(240) }}
                        </p>

                        <div class="mt-auto flex flex-wrap items-center justify-between gap-4 pt-7">
                            <span class="inline-flex items-center gap-1.5 text-xs text-gray-400">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                {{ number_format($featured->views, 0, ',', '.') }} visualizações
                            </span>

                            <a href="{{ $featured->url }}" class="inline-flex items-center gap-2 rounded-pill bg-navy px-6 py-3 text-sm font-bold text-white transition hover:bg-navy-light hover:shadow-[0_8px_24px_rgba(15,39,68,0.25)]">
                                Ler artigo
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </article>

                @if ($posts->count() > 1)
                    <div class="mt-12 flex flex-wrap items-end justify-between gap-4">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-blue-600">Continue lendo</p>
                            <h2 class="mt-2 font-heading text-2xl font-bold text-navy">Últimos artigos</h2>
                        </div>

                        <span class="text-sm text-gray-400">
                            {{ $posts->total() }} {{ $posts->total() === 1 ? 'publicação' : 'publicações' }}
                        </span>
                    </div>

                    <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($posts->skip(1) as $post)
                            @include('web.components.blog-card', ['post' => $post])
                        @endforeach
                    </div>
                @endif

                @if ($posts->hasPages())
                    <div class="mt-14">
                        {{ $posts->links() }}
                    </div>
                @endif
            @else
                <div class="rounded-card border border-gray-200 bg-white px-6 py-20 text-center shadow-sm">
                    <span class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-blue-50 text-blue-600">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H7a2 2 0 00-2 2v12a2 2 0 002 2h10"/>
                        </svg>
                    </span>

                    <h2 class="mt-5 font-heading text-xl font-bold text-navy">Nenhum artigo encontrado</h2>
                    <p class="mx-auto mt-2 max-w-md text-sm leading-relaxed text-gray-500">
                        Ainda não há publicações nesta categoria. Explore todos os conteúdos disponíveis.
                    </p>

                    <a href="{{ route('web.blog.index') }}" class="mt-6 inline-flex items-center gap-2 rounded-pill bg-navy px-6 py-3 text-sm font-bold text-white transition hover:bg-navy-light">
                        Ver todos os artigos
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                </div>
            @endif
        </div>
    </section>
@endsection
