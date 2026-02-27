@extends("web.$configuracoes->template.master.master")

@section('content')
<!-- Banner start -->
<div class="blog-banner">
    <div class="container">
        <div class="breadcrumb-area">
            <h1>Blog</h1>
            <ul class="breadcrumbs">
                <li><a href="{{route('web.home')}}">Início</a></li>
                <li class="active"><a href="{{route('web.blog.index')}}">Blog</a></li>
            </ul>
        </div>
    </div>
</div>
<!-- Banner end -->

<!-- Blog body start -->
<div class="blog-body content-area py-10">
    <div class="container mx-auto px-4">
        @if($posts && $posts->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($posts as $art)
                    <article class="bg-white rounded-xl shadow-md overflow-hidden flex flex-col h-full">
                        <!-- Imagem -->
                        <figure class="relative w-full h-80 overflow-hidden">
                            <img src="{{ $art->cover() }}" alt="{{ $art->title }}" 
                                 class="w-full h-full object-cover transition-transform duration-300 hover:scale-105">
                        </figure>

                        <!-- Autor -->
                        @if ($art->autor && $art->user)
                            <div class="flex items-center gap-2 px-4 py-3 border-b">
                                <img src="{{ $art->user->url_avatar }}" alt="{{ $art->user->name }}" 
                                     class="w-16 h-16 rounded-full object-cover">
                                <span class="text-md font-medium text-gray-700">{{ $art->user->name }}</span>
                            </div>
                        @endif

                        <!-- Detalhes -->
                        <div class="flex flex-col flex-1 p-4">
                            @php
                                $tipo = $art->type == 'noticia' ? 'noticia' : 'artigo';
                            @endphp

                            <h4 class="text-md font-semibold text-gray-800 mb-2 line-clamp-2">
                                <a href="{{ route('web.blog.'.$tipo,['slug' => $art->slug]) }}" 
                                   class="hover:text-teal-600 transition-colors">
                                    {{ $art->title }}
                                </a>
                            </h4>

                            <p class="text-md text-gray-600 flex-1 mb-3">
                                {{ Str::limit(strip_tags($art->content_web), 160) }}
                            </p>

                            <a href="{{ route('web.blog.'.$tipo,['slug' => $art->slug]) }}" 
                               class="mt-auto inline-block text-teal-600 hover:text-teal-800 text-md font-medium">
                                Leia +
                            </a>
                        </div>
                    </article>
                @endforeach
                <!-- Paginação -->
                <div class="mt-8">
                    {{ $posts->links('vendor.pagination.tailwind') }}
                </div>
            </div>
        @else
            <div class="col-span-3">
                <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Desculpe!</strong>
                    <span class="block">Não encontramos nenhum post publicado!</span>
                </div>
            </div>
        @endif
    </div>
</div>
<!-- Blog body end -->
@endsection