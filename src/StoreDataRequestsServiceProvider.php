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
        if(!file_exists(base_path('stubs/')))
        {
            mkdir(base_path('stubs/'));
        }
        $scan = scandir(__DIR__.'/../stubs');
        foreach($scan as $file)
        {
            if (!is_dir(base_path('stubs')."/$file")) {
                copy(__DIR__.'/../stubs/'.$file, base_path('stubs/'.$file));
            }
        }
    }
}
