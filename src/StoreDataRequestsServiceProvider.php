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
        if(!file_exists(base_path('stubs/')))
        {
            mkdir(base_path('stubs/'));
        }
        copy(__DIR__.'/../stubs/controller.stub', base_path('stubs/controller.stub'));
        copy(__DIR__.'/../stubs/model.stub', base_path('stubs/model.stub'));
    }
}
