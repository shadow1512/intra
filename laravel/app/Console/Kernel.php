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
        Commands\buildsearchindexdelta::class,
        Commands\makeXmlToUpdateAD::class,
        Commands\makeCsvDirectoryInfographics::class,
        Commands\updatedirectoryfromoldts::class,
        Commands\updatedirectoryfromad::class,
        Commands\updatedirectoryfromadgroups::class,
        Commands\makeXmlToCreateADStructure::class,
        Commands\syncIssuesWithRedmine::class,
        Commands\getIssueStatusesFromRedmine::class,
        Commands\syncBookingsWithRedmine::class,
        Commands\profileupdatesnotification::class,
        Commands\createmoderator::class,
        //Commands\getdinnerbills::class,
        Commands\fixchefs::class,
        Commands\makeCsvWithPeopleList::class,
        Commands\uploadparseclog::class,
        Commands\updateuserpresence::class,
        Commands\updateuserpresencemidnight::class,
        Commands\commiteridfiller::class,
        Commands\createarchiverecords::class,
        Commands\updatearchiverecords::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('adstaff:import')->hourly();
        $schedule->command('searchindex:createdaily')->dailyAt('02:00');
        $schedule->command('searchindexdelta:createhourly')->hourlyAt(10);
        $schedule->command('adxml:create')->daily();
        $schedule->command('maindepcsv:create')->daily();
        $schedule->command('dinnerbills:get')->daily();
        $schedule->command('syncissues:start')->everyFiveMinutes();
        $schedule->command('issuestatus:get')->everyFiveMinutes();
        $schedule->command('syncbookings:start')->everyFiveMinutes();
        $schedule->command('profileupdate:inform')->everyFiveMinutes();
        $schedule->command('parsec:update')->everyTenMinutes();
        $schedule->command('presence:update')->everyFiveMinutes();
        $schedule->command('presencemidnight:update')->daily();
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
