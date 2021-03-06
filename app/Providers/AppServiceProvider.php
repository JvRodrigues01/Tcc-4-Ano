<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Repositories\Interfaces\Admin\UsuarioInterface', 'App\Repositories\Admin\UsuarioRepository');
        $this->app->bind('App\Repositories\Interfaces\Admin\PlacaInterface', 'App\Repositories\Admin\PlacaRepository');
        $this->app->bind('App\Repositories\Interfaces\Admin\ClienteInterface', 'App\Repositories\Admin\ClienteRepository');
        $this->app->bind('App\Repositories\Interfaces\Admin\ResidenciaInterface', 'App\Repositories\Admin\ResidenciaRepository');
        $this->app->bind('App\Repositories\Interfaces\Admin\LogInterface', 'App\Repositories\Admin\LogRepository');
    }
}
