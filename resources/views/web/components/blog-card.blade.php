@php
    $categoria = $post->categoryObject;
@endphp

<a
    href="{{ $post->url }}"
    class="group flex h-full flex-col overflow-hidden rounded-card border border-gray-200 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:border-gray-300 hover:shadow-card"
>
    <div class="relative aspect-[4/3] overflow-hidden bg-gray-100">
        <img
            src="{{ $post->cover() }}"
            alt="{{ $post->title }}"
            loading="lazy"
            class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
        >

        @if ($categoria)
            <span class="absolute left-4 top-4 rounded-pill bg-white/95 px-3 py-1.5 text-[11px] font-bold uppercase tracking-wide text-navy shadow-sm backdrop-blur-sm">
                {{ $categoria->title }}
            </span>
        @endif
    </div>

    <div class="flex flex-1 flex-col p-5">
        <div class="flex flex-wrap items-center gap-x-2 gap-y-1 text-xs text-gray-400">
            @if ($post->publish_at)
                <span class="inline-flex items-center gap-1.5">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v13a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1z"/>
                    </svg>
                    {{ $post->publish_at }}
                </span>
            @endif

            <span class="h-1 w-1 rounded-full bg-gray-300" aria-hidden="true"></span>

            <span>{{ max(1, (int) $post->readingTime) }} min de leitura</span>
        </div>

        <h2 class="mt-3 font-heading text-lg font-bold leading-snug text-navy transition-colors group-hover:text-primary line-clamp-2">
            {{ $post->title }}
        </h2>

        <p class="mt-2 text-sm leading-relaxed text-gray-500 line-clamp-2">
            {{ $post->summary(140) }}
        </p>

        <div class="mt-auto flex items-center justify-between gap-3 pt-5">
            <span class="inline-flex items-center gap-1.5 text-xs text-gray-400">
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                {{ number_format($post->views, 0, ',', '.') }}
            </span>

            <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-primary transition-colors group-hover:text-primary-dark">
                Ler artigo
                <svg class="h-3.5 w-3.5 transition-transform group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </span>
        </div>
    </div>
</a>
