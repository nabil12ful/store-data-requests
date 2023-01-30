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
        $src = __DIR__.'/../stubs/';
        $dest = base_path('/');
        shell_exec("cp -r $src $dest");
    }
}
