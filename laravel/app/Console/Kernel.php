<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
        Commands\buildsearchindex::class,
        Commands\makeXmlToUpdateAD::class,
        Commands\makeCsvDirectoryInfographics::class,
        Commands\updatedirectoryfromoldts::class,
        Commands\updatedirectoryfromad::class,
        Commands\makeXmlToCreateADStructure::class,
        Commands\syncIssuesWithRedmine::class,
        Commands\getIssueStatusesFromRedmine::class,
        Commands\syncBookingsWithRedmine::class,
        Commands\profileupdatesnotification::class,
        Commands\createmoderator::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('searchindex:create')->hourly();
        $schedule->command('addxml:create')->daily();
        $schedule->command('maindepcsv:create')->daily();
        $schedule->command('syncissues:start')->everyFiveMinutes();
        $schedule->command('issuestatus:get')->everyFiveMinutes();
        $schedule->command('syncbookings:start')->everyFiveMinutes();
        $schedule->command('profileupdate:inform')->everyFiveMinutes();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
