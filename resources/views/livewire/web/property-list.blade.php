<div>
    @if (!empty($title))
        <div class="sub-banner overview-bgi" style="background: rgba(0, 0, 0, 0.04) url({{$configuracoes->getheadersite()}}) top left repeat;">
            <div class="overlay">
                <div class="container">
                    <div class="breadcrumb-area">
                        <h1 style="font-size: 36px;">{{$title}}</h1>
                        <ul class="breadcrumbs">
                            <li><a href="{{route('web.home')}}">Início</a></li>
                            <li class="active">{{$title}}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div class="properties-details-page content-area">
        <div class="content-area featured-properties">
            <div class="container">
                <div class="row">
                    <div class="filtr-container">
                        @forelse($properties as $property)
                            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                <div class="property" style="min-height: 370px !important;">                                
                                    <div class="property-img">
                                        <div class="property-tag button alt featured">
                                            Referência: {{$property->reference}}
                                        </div>
                                        <div class="property-tag button sale">
                                            @if($property->sale && !$property->location)
                                                Venda
                                            @elseif($property->sale && $property->location)
                                                Venda/Locação
                                            @else
                                                Locação
                                            @endif
                                        </div>
                                        <div class="property-price">
                                            @if($property->display_values)
                                                @php
                                                    $venda = ($property->sale_value && !empty($property->sale_value))
                                                        ? 'R$' . number_format($property->sale_value, 0, ',', '.')
                                                        : null;

                                                    $aluguel = ($property->rental_value && !empty($property->rental_value))
                                                        ? 'R$' . number_format($property->rental_value, 0, ',', '.') . '/'. $property->getLocationPeriod()
                                                        : null;
                                                @endphp

                                                @if($venda && $aluguel)
                                                    Venda: {{ $venda }} <br> Locação: {{ $aluguel }}
                                                @elseif($venda)
                                                    Venda: {{ $venda }}
                                                @elseif($aluguel)
                                                    Locação: {{ $aluguel }}
                                                @else
                                                    Sob Consulta
                                                @endif
                                            @endif
                                        </div>
                                        <img src="{{$property->cover()}}" alt="{{$property->title}}" class="img-responsive"/>
                                        <div class="property-overlay">
                                            <a href="{{route('web.property',['slug' => $property->slug])}}" class="overlay-link">
                                                <i class="fa fa-link"></i>
                                            </a>  
                                            @if($property->images->count())
                                                <button 
                                                    type="button" 
                                                    class="overlay-link open-gallery-btn"
                                                    data-images='@json($property->images->pluck("url_image"))'
                                                >
                                                    <i class="fa fa-expand"></i>
                                                </button>
                                            @endif 
                                            @auth
                                                @if(auth()->user()->canEditProperties())
                                                    <a 
                                                        href="{{ route('property.edit', $property->id) }}"
                                                        class="absolute top-2 right-2 z-50 bg-black/70 text-white hover:text-white px-4 py-3 rounded text-sm hover:bg-black"
                                                        title="Editar imóvel"
                                                    >
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                @endif
                                            @endauth
                                        </div>
                                    </div>
                                    <div class="property-content" style="padding-bottom: 0px !important;">
                                        <h1 class="title">
                                            <a href="{{route('web.property',['slug' => $property->slug])}}">{{$property->title}}</a>
                                        </h1>
                                        @if ($property->neighborhood)
                                            <h3 class="property-address">
                                                <a href="{{route('web.property',['slug' => $property->slug])}}">
                                                    <i class="fa fa-map-marker"></i> {{$property->neighborhood}}, {{$property->city}} / {{$property->state}}
                                                </a>
                                            </h3>                                        
                                        @endif
                                        <ul class="facilities-list clearfix">
                                            @if ($property->total_area)
                                                <li>
                                                    <i class="flaticon-square-layouting-with-black-square-in-east-area"></i>
                                                    <span>{{$property->total_area}} {{$property->measures}}</span>
                                                </li>                                            
                                            @endif
                                            @if ($property->dormitories)
                                                <li>
                                                    <i class="flaticon-bed"></i>
                                                    <span>{{$property->dormitories}}</span>
                                                </li>                                            
                                            @endif
                                            @if ($property->bathrooms)
                                                <li>
                                                    <i class="flaticon-holidays"></i>
                                                    <span>{{$property->bathrooms}}</span>
                                                </li>                                            
                                            @endif
                                            @if ($property->garage)
                                                <li>
                                                    <i class="flaticon-vehicle"></i>
                                                    <span>{{$property->garage}}</span>
                                                </li>                                            
                                            @endif                                        
                                        </ul>                            
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 col-span-full text-center mt-8">Nenhum imóvel encontrado!</p>
                        @endforelse 
                        <div 
                            x-data="galleryRoot()" 
                            x-init="init()" 
                            x-cloak
                        >
                            <!-- Modal Lightbox -->
                            <template x-if="open">
                                <div 
                                    x-show="open" 
                                    x-transition.opacity 
                                    class="fixed inset-0 bg-black/90 flex items-center justify-center z-[1000] touch-none"
                                    @wheel="onWheel($event)"
                                    @touchstart="onTouchStart($event)"
                                    @touchmove="onTouchMove($event)"
                                    @touchend="onTouchEnd($event)"
                                >
                                    <!-- Navegação -->
                                    <button 
                                        @click="prev()" 
                                        class="absolute left-4 md:left-8 text-white text-4xl font-bold select-none z-50"
                                    >&#10094;</button>

                                    <!-- Imagem -->
                                    <div class="relative">
                                        <img 
                                            :src="currentImage()" 
                                            :style="imageStyle()" 
                                            @click="toggleZoom()" 
                                            alt="Imagem" 
                                            class="max-h-[80vh] max-w-[90vw] rounded-lg shadow-lg transition-transform duration-300 cursor-zoom-in"
                                        >
                                    </div>

                                    <button 
                                        @click="next()" 
                                        class="absolute right-4 md:right-8 text-white text-4xl font-bold select-none z-50"
                                    >&#10095;</button>

                                    <!-- Fechar -->
                                    <button 
                                        @click="close()" 
                                        class="absolute top-4 right-6 text-white text-4xl font-bold z-50 select-none"
                                    >&times;</button>

                                    <!-- Indicador -->
                                    <div class="absolute bottom-4 text-gray-300 text-sm select-none">
                                        <span x-text="current + 1"></span> / <span x-text="images.length"></span>
                                    </div>
                                </div>
                            </template>
                        </div>
                        @if ($properties->hasMorePages())
                            <div class="col-12 mt-8 p-4 text-center">
                                <button wire:click="loadMore"
                                    class="px-6 py-2 bg-teal-500 hover:bg-teal-600 text-white rounded-lg font-medium">
                                    Carregar mais imóveis
                                </button>
                            </div>
                        @endif
                    </div>  
                </div>
            </div>
        </div>
    </div>
</div>
