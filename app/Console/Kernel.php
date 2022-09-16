<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Jobs\CreateSuscriptionOperations;
use App\Models\config;
use Illuminate\Support\Facades\DB;
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
        // $schedule->command('inspire')->hourly();

        $schedule->job(new CreateSuscriptionOperations())->everyMinute()->when(function () {
            $config = config::first();
            if($config){
                return $config->automatic_operations ? true : false;
            }else{
                return false;
            }
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

        $schedule->exec("bash /var/www/html/nginx/AccessLog.sh")->daily();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}