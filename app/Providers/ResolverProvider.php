<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\ResolverService;
use Illuminate\Support\ServiceProvider;

class ResolverProvider extends ServiceProvider
{

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('hypercemail.resolver', function () {
            return new ResolverService();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}