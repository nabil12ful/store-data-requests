<?php

namespace Nabil\StoreDataRequests\Commands;

use Illuminate\Console\Command;

class StoreDataRequests extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'StoreDataRequests:publish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish Controller & Model stubs for StoreDataRequests';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $path = base_path('stubs');
        // $controller = file_get_contents(__DIR__.'/../stubs/');
        $this->copy(__DIR__.'/../stubs', $path);
        $this->info('Stubs folder is published.');
        return Command::SUCCESS;
    }
}
