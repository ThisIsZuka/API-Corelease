<?php

namespace App\Console;

use App\Http\Controllers\API_NCB_FORMATTER_v13;
use DateTime;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
        Commands\Daily365::class,
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
        $schedule->command('cron:Daily365')->daily();

        $schedule->call(function () {
            $NCB_formatter = new API_NCB_FORMATTER_v13;
            $d = new DateTime();
            $NCB_formatter->generate($d->format('Y-m-d'));
        })->lastDayOfMonth('23:59');

        // $schedule->call(function () {
        //     $NCB_formatter = new API_NCB_FORMATTER_v13;
        //     $NCB_formatter->generate('2022-12-31');
        // })->name('generate_NCB_file')->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
