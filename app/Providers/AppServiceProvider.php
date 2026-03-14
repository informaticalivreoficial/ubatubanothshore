<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
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
        Schema::defaultStringLength(191);

        try {
            $Links = \App\Models\Menu::whereNull('id_pai')
                            ->orderby('created_at', 'DESC')
                            ->available()
                            ->get();        
            View()->share('Links', $Links);

            $configuracoes = \App\Models\Config::first(); 
            View()->share('configuracoes', $configuracoes);
        } catch (\Exception $e) {
            View()->share('Links', collect());
            View()->share('configuracoes', null);
        }

        Paginator::useBootstrap();         
    }
}
