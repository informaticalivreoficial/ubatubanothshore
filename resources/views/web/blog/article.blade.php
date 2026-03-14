@extends("web.layouts.app")

@section('content')    

<section class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-6">

        <div class="grid lg:grid-cols-3 gap-12">

            <!-- CONTEÚDO -->
            <div class="lg:col-span-2 order-2 lg:order-1">

                <!-- Título -->
                <h2 class="text-3xl font-bold text-gray-900 mb-4">
                    {{ $post->title }}
                </h2>                

                <!-- Conteúdo -->
                <div class="prose max-w-none prose-gray mb-10">
                    {!! $post->content !!}
                </div>

                <!-- GALERIA -->
                @if ($post->images()->count())
                    <div class="mt-12">
                        <h3 class="text-xl font-semibold text-gray-900 mb-6">
                            Galeria
                        </h3>

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

                <!-- Compartilhar -->
                @php
                    $url = urlencode(request()->fullUrl());
                    $title = urlencode($post->title);
                @endphp

                <div class="mt-10 pt-8 border-t border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-900 mb-6">
                        Compartilhe
                    </h3>
                    <div class="flex flex-wrap gap-3">

                        <!-- Facebook -->
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ $url }}"
                        target="_blank"
                        class="w-10 h-10 flex items-center justify-center rounded-full bg-[#1877F2] text-white hover:scale-110 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                                <path d="M22 12a10 10 0 10-11.63 9.87v-6.99h-2.8V12h2.8V9.8c0-2.76 1.64-4.3 4.15-4.3 1.2 0 2.45.21 2.45.21v2.7h-1.38c-1.36 0-1.78.84-1.78 1.7V12h3.03l-.48 2.88h-2.55v6.99A10 10 0 0022 12z"/>
                            </svg>
                        </a>

                        <!-- X -->
                        <a href="https://twitter.com/intent/tweet?text={{ $title }}&url={{ $url }}"
                        target="_blank"
                        class="w-10 h-10 flex items-center justify-center rounded-full bg-black text-white hover:scale-110 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                                <path d="M18.244 2H21l-6.563 7.502L22 22h-6.828l-5.341-7.03L3.463 22H1l7.02-8.02L2 2h6.91l4.825 6.37L18.244 2z"/>
                            </svg>
                        </a>

                        {{-- WhatsApp --}}
                        <a href="#"
                        onclick="shareWhatsApp(event)"
                        target="_blank"
                        class="w-10 h-10 flex items-center justify-center rounded-full bg-[#25D366] text-white hover:scale-110 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                                <path d="M20.52 3.48A11.93 11.93 0 0012.04 0C5.43 0 .1 5.33.1 11.94c0 2.1.55 4.15 1.6 5.96L0 24l6.27-1.64a11.92 11.92 0 005.77 1.47h.01c6.61 0 11.94-5.33 11.94-11.94 0-3.19-1.24-6.19-3.47-8.41zM12.05 21.8h-.01a9.87 9.87 0 01-5.02-1.37l-.36-.21-3.72.97.99-3.63-.23-.37a9.87 9.87 0 01-1.51-5.24c0-5.46 4.45-9.9 9.91-9.9 2.65 0 5.14 1.03 7.01 2.91a9.84 9.84 0 012.89 7c0 5.46-4.45 9.9-9.91 9.9zm5.44-7.37c-.3-.15-1.76-.87-2.03-.97-.27-.1-.46-.15-.65.15-.2.3-.75.97-.92 1.17-.17.2-.34.22-.64.07-.3-.15-1.26-.46-2.4-1.46-.88-.79-1.48-1.76-1.66-2.06-.17-.3-.02-.46.13-.61.13-.13.3-.34.45-.5.15-.17.2-.3.3-.5.1-.2.05-.37-.02-.52-.07-.15-.65-1.57-.9-2.15-.24-.57-.49-.5-.65-.51h-.55c-.2 0-.52.07-.8.37-.27.3-1.04 1.02-1.04 2.48 0 1.46 1.06 2.87 1.21 3.07.15.2 2.08 3.17 5.05 4.45.7.3 1.25.48 1.68.62.7.22 1.33.19 1.83.12.56-.08 1.76-.72 2.01-1.41.25-.7.25-1.3.17-1.41-.07-.1-.27-.17-.57-.32z"/>
                            </svg>
                        </a>

                        <!-- LinkedIn -->
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ $url }}"
                        target="_blank"
                        class="w-10 h-10 flex items-center justify-center rounded-full bg-[#0A66C2] text-white hover:scale-110 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                                <path d="M4.98 3.5C4.98 4.88 3.86 6 2.49 6S0 4.88 0 3.5 1.12 1 2.49 1s2.49 1.12 2.49 2.5zM0 8h5v16H0V8zm7.5 0h4.78v2.16h.07c.67-1.27 2.3-2.6 4.73-2.6 5.06 0 6 3.33 6 7.66V24h-5v-7.84c0-1.87-.03-4.28-2.61-4.28-2.61 0-3.01 2.04-3.01 4.15V24h-5V8z"/>
                            </svg>
                        </a>

                        <!-- Telegram -->
                        <a href="https://t.me/share/url?url={{ $url }}&text={{ $title }}"
                        target="_blank"
                        class="w-10 h-10 flex items-center justify-center rounded-full bg-[#229ED9] text-white hover:scale-110 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                                <path d="M9.993 15.674l-.4 4.326c.573 0 .822-.246 1.123-.54l2.694-2.577 5.586 4.09c1.024.563 1.75.267 2.004-.95l3.63-17.037c.337-1.56-.563-2.17-1.558-1.8L1.357 9.63c-1.516.592-1.495 1.44-.258 1.823l5.66 1.77 13.148-8.29c.62-.41 1.184-.183.72.227"/>
                            </svg>
                        </a>

                        <!-- Email -->
                        <a href="mailto:?subject={{ $title }}&body={{ $url }}"
                        class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-600 text-white hover:scale-110 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                                <path d="M12 13.065L.015 4.5A2 2 0 012 3h20a2 2 0 011.985 1.5L12 13.065zM0 6.697V19a2 2 0 002 2h20a2 2 0 002-2V6.697l-12 8.25L0 6.697z"/>
                            </svg>
                        </a>

                    </div>
                </div>

            </div>
            
            <aside class="lg:col-span-1 order-1 lg:order-2">
                <div class="sticky top-28">
                    <div class="rounded-2xl overflow-hidden shadow-lg">
                        <img 
                            src="{{ $post->cover() }}" 
                            alt="{{ $post->title }}"
                            class="w-full h-96 object-cover"
                        >
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
            Thumbs: {
            autoStart: true,
            },
        });
    </script>

    <script>
        function shareWhatsApp(event) {
            event.preventDefault();

            const url = encodeURIComponent(window.location.href);
            const text = encodeURIComponent(document.title);
            const message = text + " " + url;

            const isMobile = /Android|iPhone|iPad|iPod|Opera Mini|IEMobile|WPDesktop/i.test(navigator.userAgent);

            const whatsappUrl = isMobile
                ? `https://api.whatsapp.com/send?text={{ $title }}%20{{ $url }}`
                : `https://web.whatsapp.com/send?text={{ $title }}%20{{ $url }}`;

            window.open(whatsappUrl, '_blank');
        }
    </script>
@endpush