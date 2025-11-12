<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\CuentaCobro;
use App\Policies\CuentaCobroPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        CuentaCobro::class => CuentaCobroPolicy::class,
    ];

    public function boot(): void
    {
        //
    }
}
