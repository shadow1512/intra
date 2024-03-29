<?php

namespace App\Console\Commands;

use App\Booking;
use Illuminate\Console\Command;

class syncBookingsWithRedmine extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'syncbookings:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Syncing room bookings with technical issues with Redmine';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(Booking $book)
    {
        //
        $book->syncToRedmine();
    }
}
