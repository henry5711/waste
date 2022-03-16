<?php

namespace App\Console;

use App\Jobs\CreateSuscriptionOperations;
use App\Models\config;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\DB;
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
        $schedule->job(new CreateSuscriptionOperations())->everyMinute()->when(function () {
            $config = config::first();

            return $config->automatic_operations;
        })->withoutOverlapping();

        $schedule->command('queue:retry all')->everyMinute()->when(function () {
            $job = DB::table('failed_jobs')->select('*')->get();

            return (count($job) >= 3) ? true : false;
        })->withoutOverlapping();

        $schedule->command('queue:work --stop-when-empty')->everyMinute()->when(function () {
            $job = DB::table('jobs')->select('*')->get();
            return (count($job) > 0) ? true : false;
        })->withoutOverlapping();

        $schedule->command('queue:flush')->everyMinute()->when(function () {
            $job = DB::table('failed_jobs')->select('*')->get();

            return (count($job) >= 15) ? true : false;
        })->withoutOverlapping();
    }
}
