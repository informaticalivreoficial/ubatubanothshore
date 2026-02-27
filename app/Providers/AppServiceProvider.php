<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //URL::forceScheme('https');

        Schema::defaultStringLength(191);
        Blade::aliasComponent('admin.components.message', 'message');

        //Menu
        $Links = \App\Models\Menu::whereNull('id_pai')->orderby('created_at', 'DESC')
                        ->available()
                        ->get();        
        View()->share('Links', $Links);

        $configuracoes = \App\Models\Config::first(); 
        View()->share('configuracoes', $configuracoes);

        $postsfooter = \App\Models\Post::orderBy('created_at', 'DESC')
                            ->where('type', 'artigo')
                            ->orWhere('type', 'noticia')
                            ->postson()
                            ->limit(3)
                            ->get();
        View()->share('postsfooter', $postsfooter);

        $lancamentos = \App\Models\Property::where('highlight', 1)
                        ->available()
                        ->get();
        View()->share('lancamentoMenu', $lancamentos);

        Paginator::useBootstrap();
        
    }
}
