@extends("web.layouts.app")

@section('content')

{{-- HERO --}}
<section class="bg-gradient-to-b from-gray-50 to-white border-b">
    <div class="max-w-7xl mx-auto px-6 py-14">

        @isset($category)
            <div class="text-sm text-gray-400 mb-3">
                <a href="{{ route('web.blog.index') }}" class="hover:text-indigo-600 transition">Blog</a>
                <span class="mx-2">/</span>
                <span class="text-gray-600">{{ $category->title }}</span>
            </div>
        @endisset

        <h1 class="text-4xl font-bold text-gray-900">
            {{ isset($category) ? $category->title : 'Blog' }}
        </h1>

        @isset($category)
            @if($category->content)
                <p class="text-gray-500 mt-3 max-w-2xl">
                    {{ $category->content }}
                </p>
            @endif
        @else
            <p class="text-gray-500 mt-3 max-w-2xl">
                Artigos, dicas e conteúdos para ajudar você.
            </p>
        @endisset

        <p class="text-sm text-gray-400 mt-4">
            {{ $posts->total() }} artigos publicados
        </p>

    </div>
</section>


<section class="py-16 bg-gray-50">
<div class="max-w-7xl mx-auto px-6">

@if($posts->count())

{{-- POST EM DESTAQUE --}}
@php
    $featured = $posts->first();
@endphp

<a href="{{ route('web.blog.artigo', $featured->slug) }}"
   class="block mb-16 group">

    <div class="grid lg:grid-cols-2 gap-10 items-center">

        <div class="overflow-hidden rounded-2xl">
            <img
                src="{{ $featured->cover() }}"
                alt="{{ $featured->title }}"
                class="w-full h-[360px] object-cover group-hover:scale-105 transition duration-500"
            >
        </div>

        <div class="space-y-5">

            <span class="text-xs font-semibold text-indigo-600 uppercase tracking-wide">
                Artigo em destaque
            </span>

            <h2 class="text-3xl font-bold text-gray-900 group-hover:text-indigo-600 transition leading-tight">
                {{ $featured->title }}
            </h2>

            <p class="text-gray-500 line-clamp-3">
                {{ $featured->excerpt ?? Str::limit(strip_tags($featured->content),160) }}
            </p>

            <div class="flex items-center gap-4 text-sm text-gray-400">
                @if($featured->publish_at)
                    <span>{{ $featured->publish_at }}</span>
                @endif

                <span class="flex items-center gap-1">
                    👁 {{ number_format($featured->views,0,',','.') }}
                </span>
            </div>

        </div>

    </div>

</a>


{{-- GRID DE POSTS --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">

@foreach($posts->skip(1) as $post)

<a href="{{ route('web.blog.artigo', $post->slug) }}"
   class="group block bg-white rounded-2xl overflow-hidden border border-gray-100 hover:shadow-xl transition duration-300">

    {{-- IMAGEM --}}
    <div class="relative overflow-hidden">

        <img
            src="{{ $post->cover() }}"
            alt="{{ $post->title }}"
            class="w-full h-52 object-cover group-hover:scale-105 transition duration-500"
        >

    </div>


    {{-- CONTEUDO --}}
    <div class="p-5 space-y-3">

        <div class="flex items-center justify-between text-xs text-gray-400">

            @if($post->publish_at)
                <span>{{ $post->publish_at }}</span>
            @endif

            <span class="flex items-center gap-1">
                👁 {{ number_format($post->views,0,',','.') }}
            </span>

        </div>


        <h2 class="text-lg font-semibold text-gray-900 group-hover:text-indigo-600 transition line-clamp-2 leading-snug">
            {{ $post->title }}
        </h2>


        @if($post->tags)
        <div class="flex flex-wrap gap-2 pt-1">

            @foreach(array_slice(array_map('trim', explode(',', $post->tags)),0,3) as $tag)

                <span class="text-xs px-2 py-1 bg-indigo-50 text-indigo-600 rounded-md">
                    #{{ $tag }}
                </span>

            @endforeach

        </div>
        @endif

    </div>

</a>

@endforeach

</div>


{{-- PAGINAÇÃO --}}
@if($posts->hasPages())
<div class="mt-16 flex justify-center">
    {{ $posts->links() }}
</div>
@endif


@else


{{-- ESTADO VAZIO --}}
<div class="text-center py-24">

    <div class="text-5xl mb-4">
        📭
    </div>

    <h3 class="text-xl font-semibold text-gray-700">
        Nenhum artigo encontrado
    </h3>

    <p class="text-gray-400 mt-2">
        Ainda não há artigos publicados nesta categoria.
    </p>

    <a href="{{ route('web.blog.index') }}"
       class="inline-block mt-6 px-6 py-3 bg-indigo-600 text-white text-sm font-medium rounded-xl hover:bg-indigo-700 transition">

        Ver todos os artigos

    </a>

</div>


@endif


</div>
</section>

@endsection


@push('styles')

<style>

.line-clamp-2{
display:-webkit-box;
-webkit-line-clamp:2;
-webkit-box-orient:vertical;
overflow:hidden;
}

.line-clamp-3{
display:-webkit-box;
-webkit-line-clamp:3;
-webkit-box-orient:vertical;
overflow:hidden;
}

</style>

@endpush


@push('scripts')
@endpush
