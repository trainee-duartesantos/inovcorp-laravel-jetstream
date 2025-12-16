<?php

namespace App\Providers;

use App\Http\Middleware\AdminOnly;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use App\Models\Requisicao;
use App\Observers\RequisicaoObserver;

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
         Requisicao::observe(RequisicaoObserver::class);
    }
}
