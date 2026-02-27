@extends("web.$configuracoes->template.master.master")

@section('content')
<div class="blog-body content-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-8 col-xs-12 col-md-push-4">
                <!-- Blog box start -->
                <div class="thumbnail blog-box clearfix">                
                    <img src="{{$post->cover()}}" alt="{{$post->title}}" class="img-responsive"/>
                    <div class="caption detail">
                        <!--Main title -->
                        <h3 class="title">
                            {{$post->title}}
                        </h3>
                        <!-- Post meta -->
                        <div class="post-meta">
                            <span><a href="#"><i class="fa fa-user"></i>{{$post->user->name}}</a></span>
                            <span><a><i class="fa fa-calendar "></i>{{ $post->created_at->format('d M, Y') }}</a></span>
                            <span><a href="#"><i class="fa fa-bars"></i> {{$post->categoryObject->title}}</a></span>                        
                            <span><a href="#"><i class="fa fa-comments"></i>{{$post->commentsCount()}}</a></span>
                        </div>
                        <!-- Social list -->
                        <div id="shareIcons"></div>
                        {!!$post->content!!}
                        
                        @if ($post->images()->get()->count())
                            <div class="row clearfix t-s">                            
                                @foreach($post->images()->get() as $gb)
                                    <div class="col-lg-2 col-md-2 col-sm-3 col-xs-6">
                                        <div class="agent-1">
                                            <a rel="ShadowBox[galeria]" href="{{ $gb->url_image }}" class="agent-img">
                                                <img src="{{ $gb->url_cropped }}" alt="{{ $post->titulo }}" class="img-responsive">
                                            </a>
                                        </div>                                
                                    </div>
                                @endforeach                          
                            </div>
                        @endif                        
                        
                        <div class="row clearfix t-s">
                            <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                @if($postsTags->count())                                        
                                    <div class="tags-box">
                                        <h2>Tags</h2>                        
                                        <ul class="tags">                            
                                            @foreach($postsTags as $tags)
                                                @php
                                                    $array = explode(',', $tags->tags);
                                                @endphp
                                                @php
                                                    $tipo = $tags->type == 'noticia' ? 'noticia' : 'artigo';
                                                @endphp
                                                @foreach($array as $tag)
                                                    @php $tag = trim($tag); @endphp
                                                    <li>
                                                        <a href="{{ route('web.blog.'.$tipo,['slug' => $tags->slug]) }}">{{ $tag }}</a>
                                                    </li>
                                                @endforeach
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif                  
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                                <!-- Blog Share start -->
                                <div class="social-media clearfix blog-share">
                                    <h2>Compartilhe</h2>
                                    <!-- Social list -->
                                    <div class="shareIcons"></div>                                    
                                </div>
                                <!-- Blog Share end -->
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Blog box end -->

                @if ($post->comments)
                    <div class="comments-section sidebar-widget">
                        <div class="main-title-2"><h1>Comentários</h1></div>
                        <ul class="comments">                     
                            <li>
                                <div class="comment">
                                    <div class="comment-author"> 
                                        <?php
                                            $readUser = new Read;
                                            $readUser->ExeRead("usuario", "WHERE email = :emailId", "emailId={$coment['email']}");
                                            if($readUser->getResult()):
                                                $userComent = $readUser->getResult()['0'];
                                                echo '<a href="#">';
                                                echo '<img src="'.BASE.'/uploads/'.$userComent['avatar'].'" alt="'.$coment['nome'].'" />';
                                                echo '<a/>';
                                            else:
                                                $nomeComent = $coment['nome'];
                                                echo '<a href="#">';
                                                echo '<img src="'.PATCH.'/images/avatar.png" alt="'.$coment['nome'].'" />';
                                                echo '<a/>';
                                            endif;
                                        ?>                                
                                    </div>
                                    <div class="comment-content">
                                        <div class="comment-meta">
                                            <div class="comment-meta-author">
                                                <?= $coment['nome'];?>
                                            </div>
                                            <div class="comment-meta-reply">
                                                <a href="javascript:;" data-toggle="modal" data-target="#1">Responder</a>
                                            </div>
                                            <div class="comment-meta-date">
                                                <span class="hidden-xs"><?= date('d/m/Y', strtotime($coment['data']));?></span>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="comment-body">
                                            <p><?= $coment['comentario'];?></p>
                                        </div>
                                    </div>
                                </div>

                                <ul>
                                    <li>
                                        <div class="comment">
                                            <div class="comment-author">
                                                <?php
                                                $readUser = new Read;
                                                $readUser->ExeRead("usuario", "WHERE email = :emailUser", "emailUser={$comentarioresp['email']}");
                                                if($readUser->getResult()):
                                                        $userComent = $readUser->getResult()['0'];
                                                        echo '<a href="#">';
                                                        echo '<img src="'.BASE.'/uploads/'.$userComent['avatar'].'" alt="'.$userComent['nome'].'"/>';
                                                        echo '<a/>';
                                                else:
                                                        echo '<a href="#">';
                                                        echo '<img src="'.PATCH.'/images/avatar.png" alt="'.$comentarioresp['nome'].'"/>';
                                                        echo '<a/>';
                                                endif;
                                                ?> 
                                            </div>

                                            <div class="comment-content">
                                                <div class="comment-meta">
                                                    <div class="comment-meta-author">
                                                        <?= $comentarioresp['nome'];?>
                                                    </div>
                                                    
                                                    <div class="comment-meta-date">
                                                        <span class="hidden-xs"><?= date('d/m/Y', strtotime($comentarioresp['data']));?></span>
                                                    </div>
                                                </div>
                                                <div class="clearfix"></div>
                                                <div class="comment-body">                    
                                                    <p><?= $comentarioresp['comentario'];?></p>
                                                </div>
                                            </div>
                                        </div>        
                                    </li>
                                </ul>              
                            </li>  
                        </ul>
                    </div>
                    <div class="contact-1 sidebar-widget">
                        <div class="main-title-2">
                            <h1>Deixe seu Comentário</h1>
                        </div>
                        <div class="contact-form">
                            <form  action="" method="post" class="j_formsubmitcomentario">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="alertas"></div>
                                    </div>                                
                                    <div class="form_hide">
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <div class="form-group fullname">
                                            <input class="noclear" type="hidden" name="action" value="comentario" />
                                            <input class="noclear" type="hidden" name="post_id" value="<?= $id;?>" />
                                            <input type="hidden" class="noclear" name="bairro" value="" />
                                            <input type="text" class="noclear" style="display: none;" name="cidade" value="" />
                                            <input type="text" name="nome" class="input-text" placeholder="Seu Nome"/>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <div class="form-group enter-email">
                                            <input type="email" name="email" class="input-text" placeholder="Seu E-mail"/>
                                            
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 clearfix">
                                        <div class="form-group message">
                                            <textarea class="input-text" name="comentario" placeholder="Comentário"></textarea>
                                        </div>
                                    </div>                                
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <div class="form-group enter-email" style="text-align: right;">
                                            <button style="width: 100%;" type="submit" class="button-md button-theme" id="b_nome">Enviar Comentário</button>
                                        </div>
                                    </div>
                                    </div>
                                    
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
            

            <div class="col-lg-4 col-md-4 col-xs-12 col-md-pull-8">
                <div class="sidebar">
                    @if($categorias->count())                          
                        <div class="sidebar-widget category-posts">
                            <div class="main-title-2">
                                <h1>Categorias</h1>
                            </div>
                            <ul class="list-unstyled list-cat">
                                @foreach($categorias as $categoria)
                                    <li>
                                        <a href="{{route('web.blog.category',[ 'slug' => $categoria->slug ])}}">{{$categoria->title}} </a> 
                                        <span>({{$categoria->countposts()}})  </span>
                                    </li>                   
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if($postsMais->count())
                        <div class="sidebar-widget popular-posts">
                            <div class="main-title-2">
                                <h1>Mais Lidos</h1>
                            </div>
                            @foreach($postsMais as $maislidos)
                                <div class="flex items-start gap-4 mb-8">
                                    <div class="flex-shrink-0">
                                        <img class="w-24 h-24 object-cover rounded" src="{{$maislidos->cover()}}" alt="{{$maislidos->title}}">
                                    </div>
                                    @php
                                        $tipo = $maislidos->type == 'noticia' ? 'noticia' : 'artigo';
                                    @endphp
                                    <div class="flex-1">
                                        <h3 class="text-md font-semibold text-teal-400 hover:text-gray-400 transition">
                                            <a href="{{ route('web.blog.'.$tipo,['slug' => $maislidos->slug]) }}">{{$maislidos->title}}</a>
                                        </h3>
                                        <p class="text-sm text-gray-600 mt-1">{{ $maislidos->created_at->format('d M, Y') }}</p>                                
                                    </div>
                                </div> 
                            @endforeach
                        </div>                       
                    @endif                    
                    
                    @if($postsTags->count())
                        <div class="sidebar-widget tags-box">
                        <div class="main-title-2"><h1>Tags</h1></div>                        
                            <ul class="tags">                            
                                @foreach($postsTags as $tags2)
                                    @php
                                        $array = explode(',', $tags2->tags);
                                    @endphp
                                    @php
                                        $tipo = $tags2->type == 'noticia' ? 'noticia' : 'artigo';
                                    @endphp
                                    @foreach($array as $tag)
                                        @php $tag = trim($tag); @endphp
                                        <li>
                                            <a href="{{ route('web.blog.'.$tipo,['slug' => $tags2->slug]) }}">{{ $tag }}</a>
                                        </li>
                                    @endforeach                      
                                @endforeach
                            </ul>
                        </div>  
                    @endif                   
                    
                    <div class="social-media sidebar-widget clearfix">
                        <div class="main-title-2">
                            <h1>Redes Sociais</h1>
                        </div>
                        <ul class="social-list">
                            @if ($configuracoes->facebook)
                                <li>
                                    <a target="_blank" href="{{$configuracoes->facebook}}" class="facebook">
                                        <i class="fa fa-facebook"></i>
                                    </a>
                                </li>
                            @endif
                            @if ($configuracoes->twitter)
                                <li>
                                    <a target="_blank" href="{{$configuracoes->twitter}}" class="twitter">
                                        <i class="fa fa-twitter"></i>
                                    </a>
                                </li>
                            @endif
                            @if ($configuracoes->linkedin)
                                <li>
                                    <a target="_blank" href="{{$configuracoes->linkedin}}" class="linkedin">
                                        <i class="fa fa-linkedin"></i>
                                    </a>
                                </li>
                            @endif
                            @if ($configuracoes->instagram)
                                <li>
                                    <a target="_blank" href="{{$configuracoes->instagram}}" class="instagram">
                                        <i class="fa fa-instagram"></i>
                                    </a>
                                </li>
                            @endif                            
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{url(asset('frontend/'.$configuracoes->template.'/js/jsSocials/jssocials.css'))}}" />
    <link rel="stylesheet" type="text/css" href="{{url(asset('frontend/'.$configuracoes->template.'/js/jsSocials/jssocials-theme-flat.css'))}}" />
    <link rel="stylesheet" type="text/css" href="{{url(asset('frontend/'.$configuracoes->template.'/js/shadowbox/shadowbox.css'))}}"/>
@endsection

@section('js')
    <script type="text/javascript" src="{{url(asset('frontend/'.$configuracoes->template.'/js/jsSocials/jssocials.min.js'))}}"></script>
    <script type="text/javascript" src="{{url(asset('frontend/'.$configuracoes->template.'/js/shadowbox/shadowbox.js'))}}"></script>
    <script>
        Shadowbox.init();
        
        $('.shareIcons').jsSocials({
            //url: "http://www.google.com",
            showLabel: false,
            showCount: false,
            shareIn: "popup",
            shares: ["email", "twitter", "facebook", "whatsapp"]
        });
    </script>
@endsection