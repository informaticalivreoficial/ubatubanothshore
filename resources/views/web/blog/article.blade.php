@extends("web.layouts.app")

@php
    // Só processamentos leves, sem queries
    $tagsList = $post->tags
        ? array_map('trim', explode(',', $post->tags))
        : [];

    $todasTags = $postsTags->flatMap(fn($p) => array_map('trim', explode(',', $p->tags ?? '')))
        ->filter()
        ->unique()
        ->values();

    $relacionados = $postsMais->where('id', '!=', $post->id)->take(3);
@endphp

@section('content')

<section class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-6">

        <div class="grid lg:grid-cols-3 gap-12">

            {{-- CONTEÚDO PRINCIPAL --}}
            <div class="lg:col-span-2 order-2 lg:order-1 space-y-10">

                {{-- TÍTULO --}}
                <div>
                    @if($post->category)
                        @php $cat = $categorias->firstWhere('id', $post->category) @endphp
                        @if($cat)
                            <a href="{{ route('web.blog.category', $cat->slug) }}"
                                class="inline-block text-xs font-semibold uppercase tracking-widest text-indigo-600 mb-3">
                                {{ $cat->title }}
                            </a>
                        @endif
                    @endif

                    <h1 class="text-3xl font-bold text-gray-900 mb-4">
                        {{ $post->title }}
                    </h1>

                    <div class="flex items-center gap-4 text-sm text-gray-500">
                        @if($post->publish_at)
                            <span>{{ $post->publish_at }}</span>
                        @endif
                        <span>👁 {{ number_format($post->views, 0, ',', '.') }} visualizações</span>
                    </div>
                </div>

                {{-- CONTEÚDO --}}
                <div class="prose max-w-none prose-gray">
                    {!! $post->content !!}
                </div>

                {{-- TAGS DO POST --}}
                @if(!empty($tagsList))
                    <div class="pt-6 border-t border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-700 mb-3">Tags</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($tagsList as $tag)
                                <span class="px-3 py-1 text-xs bg-gray-100 text-gray-600 rounded-full">
                                    # {{ $tag }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- GALERIA --}}
                @if($post->images()->count())
                    <div class="pt-6 border-t border-gray-200">
                        <h3 class="text-xl font-semibold text-gray-900 mb-6">Galeria</h3>
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                            @foreach($post->images as $image)
                                <a href="{{ $image->url_image }}"
                                    data-fancybox="gallery"
                                    data-caption="{{ $post->title }}"
                                    class="block rounded-xl overflow-hidden shadow hover:shadow-lg transition">
                                    <img
                                        src="{{ $image->url_cropped }}"
                                        alt="{{ $post->title }}"
                                        class="w-full h-32 object-cover hover:scale-105 transition duration-300"
                                    >
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- COMPARTILHAR --}}
                @php
                    $url   = urlencode(request()->fullUrl());
                    $title = urlencode($post->title);
                @endphp
                <div class="pt-8 border-t border-gray-200">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">Compartilhe</h3>
                    <div class="flex flex-wrap gap-3">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ $url }}" target="_blank"
                            class="w-10 h-10 flex items-center justify-center rounded-full bg-[#1877F2] text-white hover:scale-110 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M22 12a10 10 0 10-11.63 9.87v-6.99h-2.8V12h2.8V9.8c0-2.76 1.64-4.3 4.15-4.3 1.2 0 2.45.21 2.45.21v2.7h-1.38c-1.36 0-1.78.84-1.78 1.7V12h3.03l-.48 2.88h-2.55v6.99A10 10 0 0022 12z"/></svg>
                        </a>
                        <a href="https://twitter.com/intent/tweet?text={{ $title }}&url={{ $url }}" target="_blank"
                            class="w-10 h-10 flex items-center justify-center rounded-full bg-black text-white hover:scale-110 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M18.244 2H21l-6.563 7.502L22 22h-6.828l-5.341-7.03L3.463 22H1l7.02-8.02L2 2h6.91l4.825 6.37L18.244 2z"/></svg>
                        </a>
                        <a href="#" onclick="shareWhatsApp(event)" target="_blank"
                            class="w-10 h-10 flex items-center justify-center rounded-full bg-[#25D366] text-white hover:scale-110 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M20.52 3.48A11.93 11.93 0 0012.04 0C5.43 0 .1 5.33.1 11.94c0 2.1.55 4.15 1.6 5.96L0 24l6.27-1.64a11.92 11.92 0 005.77 1.47h.01c6.61 0 11.94-5.33 11.94-11.94 0-3.19-1.24-6.19-3.47-8.41zM12.05 21.8h-.01a9.87 9.87 0 01-5.02-1.37l-.36-.21-3.72.97.99-3.63-.23-.37a9.87 9.87 0 01-1.51-5.24c0-5.46 4.45-9.9 9.91-9.9 2.65 0 5.14 1.03 7.01 2.91a9.84 9.84 0 012.89 7c0 5.46-4.45 9.9-9.91 9.9zm5.44-7.37c-.3-.15-1.76-.87-2.03-.97-.27-.1-.46-.15-.65.15-.2.3-.75.97-.92 1.17-.17.2-.34.22-.64.07-.3-.15-1.26-.46-2.4-1.46-.88-.79-1.48-1.76-1.66-2.06-.17-.3-.02-.46.13-.61.13-.13.3-.34.45-.5.15-.17.2-.3.3-.5.1-.2.05-.37-.02-.52-.07-.15-.65-1.57-.9-2.15-.24-.57-.49-.5-.65-.51h-.55c-.2 0-.52.07-.8.37-.27.3-1.04 1.02-1.04 2.48 0 1.46 1.06 2.87 1.21 3.07.15.2 2.08 3.17 5.05 4.45.7.3 1.25.48 1.68.62.7.22 1.33.19 1.83.12.56-.08 1.76-.72 2.01-1.41.25-.7.25-1.3.17-1.41-.07-.1-.27-.17-.57-.32z"/></svg>
                        </a>
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ $url }}" target="_blank"
                            class="w-10 h-10 flex items-center justify-center rounded-full bg-[#0A66C2] text-white hover:scale-110 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M4.98 3.5C4.98 4.88 3.86 6 2.49 6S0 4.88 0 3.5 1.12 1 2.49 1s2.49 1.12 2.49 2.5zM0 8h5v16H0V8zm7.5 0h4.78v2.16h.07c.67-1.27 2.3-2.6 4.73-2.6 5.06 0 6 3.33 6 7.66V24h-5v-7.84c0-1.87-.03-4.28-2.61-4.28-2.61 0-3.01 2.04-3.01 4.15V24h-5V8z"/></svg>
                        </a>
                        <a href="https://t.me/share/url?url={{ $url }}&text={{ $title }}" target="_blank"
                            class="w-10 h-10 flex items-center justify-center rounded-full bg-[#229ED9] text-white hover:scale-110 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M9.993 15.674l-.4 4.326c.573 0 .822-.246 1.123-.54l2.694-2.577 5.586 4.09c1.024.563 1.75.267 2.004-.95l3.63-17.037c.337-1.56-.563-2.17-1.558-1.8L1.357 9.63c-1.516.592-1.495 1.44-.258 1.823l5.66 1.77 13.148-8.29c.62-.41 1.184-.183.72.227"/></svg>
                        </a>
                        <a href="mailto:?subject={{ $title }}&body={{ $url }}"
                            class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-600 text-white hover:scale-110 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12 13.065L.015 4.5A2 2 0 012 3h20a2 2 0 011.985 1.5L12 13.065zM0 6.697V19a2 2 0 002 2h20a2 2 0 002-2V6.697l-12 8.25L0 6.697z"/></svg>
                        </a>
                    </div>
                </div>

                {{-- POSTS RELACIONADOS --}}
                @if($relacionados->count())
                    <div class="pt-8 border-t border-gray-200">

                        <h3 class="text-xl font-semibold text-gray-900 mb-6">Posts relacionados</h3>

                        <div class="grid sm:grid-cols-3 gap-6">
                            @foreach($relacionados as $rel)
                                <a href="{{ route('web.blog.artigo', $rel->slug) }}"
                                    class="group block rounded-2xl overflow-hidden bg-white border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">

                                    {{-- Imagem --}}
                                    <div class="relative overflow-hidden h-40">
                                        <img
                                            src="{{ $rel->cover() }}"
                                            alt="{{ $rel->title }}"
                                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                                        >
                                        {{-- Overlay gradiente --}}
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                    </div>

                                    {{-- Conteúdo --}}
                                    <div class="p-4 space-y-2">

                                        @if($rel->publish_at)
                                            <span class="text-xs text-gray-400">
                                                {{ $rel->publish_at }}
                                            </span>
                                        @endif

                                        <p class="text-sm font-semibold text-gray-800 group-hover:text-indigo-600 transition-colors duration-200 line-clamp-2 leading-snug">
                                            {{ $rel->title }}
                                        </p>

                                        <span class="inline-flex items-center gap-1 text-xs text-indigo-500 font-medium opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                            Ler artigo
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                            </svg>
                                        </span>

                                    </div>

                                </a>
                            @endforeach
                        </div>

                    </div>
                @endif

            </div>

            {{-- SIDEBAR --}}
            <aside class="lg:col-span-1 order-1 lg:order-2 space-y-8">
                <div class="sticky top-28 space-y-8">

                    {{-- CAPA --}}
                    <div class="rounded-2xl overflow-hidden shadow-lg">
                        <img
                            src="{{ $post->cover() }}"
                            alt="{{ $post->title }}"
                            class="w-full h-72 object-cover"
                        >
                    </div>

                    {{-- CATEGORIAS --}}
                   @if($categorias->count())
                        <div class="bg-white rounded-2xl border p-6">
                            <h3 class="text-base font-semibold text-gray-900 mb-4">Categorias</h3>
                            <ul class="space-y-2">
                                @foreach($categorias as $cat)
                                    <li>
                                        {{-- Categoria pai --}}
                                        <span class="text-sm font-medium text-gray-700">
                                            {{ $cat->title }}
                                        </span>

                                        {{-- Subcategorias --}}
                                        @if($cat->children->count())
                                            <ul class="mt-1 ml-3 space-y-1">
                                                @foreach($cat->children as $sub)
                                                    <li>
                                                        <a href="{{ route('web.blog.category', $sub->slug) }}"
                                                            class="text-xs text-gray-500 hover:text-indigo-600 transition flex items-center gap-1">
                                                            <span class="text-gray-300">└</span>
                                                            {{ $sub->title }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- MAIS VISITADOS --}}
                    @if($postsMais->count())
                        <div class="bg-white rounded-2xl border p-6">
                            <h3 class="text-base font-semibold text-gray-900 mb-4">Mais visitados</h3>
                            <ul class="space-y-4">
                                @foreach($postsMais as $i => $visitado)
                                    <li>
                                        <a href="{{ route('web.blog.artigo', $visitado->slug) }}"
                                            class="flex gap-3 group">
                                            <span class="text-2xl font-bold text-gray-100 leading-none">
                                                {{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}
                                            </span>
                                            <div>
                                                <p class="text-sm font-medium text-gray-800 group-hover:text-indigo-600 transition line-clamp-2">
                                                    {{ $visitado->title }}
                                                </p>
                                                <span class="text-xs text-gray-400">
                                                    {{ number_format($visitado->views, 0, ',', '.') }} views
                                                </span>
                                            </div>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- TAGS POPULARES --}}
                    @if($todasTags->count())
                        <div class="bg-white rounded-2xl border p-6">
                            <h3 class="text-base font-semibold text-gray-900 mb-4">Tags populares</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach($todasTags as $tag)
                                    <span class="px-3 py-1 text-xs bg-gray-100 text-gray-600 rounded-full">
                                        # {{ $tag }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

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
    <script>
        function shareWhatsApp(event) {
            event.preventDefault();
            const url   = encodeURIComponent(window.location.href);
            const text  = encodeURIComponent(document.title);
            const isMobile = /Android|iPhone|iPad|iPod|Opera Mini|IEMobile|WPDesktop/i.test(navigator.userAgent);
            const whatsappUrl = isMobile
                ? `https://api.whatsapp.com/send?text=${text}%20${url}`
                : `https://web.whatsapp.com/send?text=${text}%20${url}`;
            window.open(whatsappUrl, '_blank');
        }
    </script>
@endpush