<?php

namespace App\Console;

use App\Jobs\CreateSuscriptionOperations;
use App\Models\config;
use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\GeneratorCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //
        // $schedule->job(new CreateSuscriptionOperations)->everyMinute()->when(function(){
        //     $config = config::first();

        //     if($config->automatic_operations)
        //         return true;
        // })->withoutOverlapping();
    }
}
