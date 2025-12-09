<?php

namespace App\Providers;
use Illuminate\Support\Facades\View;
use App\Helpers\RolHelper;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
     public function boot(): void
    {
        View::composer('*', function ($view) {
            $view->with([
                'usuarioRol' => RolHelper::rolActual(),
                'esOwner' => RolHelper::esOwner(),
                'esGerente' => RolHelper::esGerente(),
                'esEncargadoVentas' => RolHelper::esEncargadoVentas(),
                'esEncargadoAlmacenes' => RolHelper::esEncargadoAlmacenes(),
            ]);
        });
    }
}
