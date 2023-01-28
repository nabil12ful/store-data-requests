<?php

namespace Nabil\StoreDataRequests;

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
            __DIR__.'/../stubs/controller.stub' => base_path('stubs'),
        ]);
        $this->publishes([
            __DIR__.'/../stubs/model.stub' => base_path('stubs'),
        ]);
    }
}
