<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ViewComposerServiceProvider extends ServiceProvider
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
          view()->composer('*', function ($view) {
        $categorias = \App\Models\categorias::all(); // Asegúrate de usar el nombre correcto del modelo
        $view->with('categorias', $categorias);
    });
    }
}
