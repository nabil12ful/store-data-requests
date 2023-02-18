<?php

namespace Nabil;

use Illuminate\Support\ServiceProvider;

class StoreDataRequestsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../stubs' => base_path('stubs'),
        ]);
    }
}
