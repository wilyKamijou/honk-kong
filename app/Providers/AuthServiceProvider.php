<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerPolicies();

          // Gate para el rol admin
        Gate::define('admin-only', function (User $user) {
            return $user->role === 'admin';
        });

        // Otro ejemplo: Gate para clientes
        Gate::define('cliente-only', function (User $user) {
            return $user->role === 'cliente';
        });
    }
}
