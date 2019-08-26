<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Adldap\Laravel\Facades\Adldap;
use App\User;
use App\Deps_Peoples;
use App\Deps_Temporal;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Config;
use Illuminate\Support\Facades\Validator;
use DB;

class updatedirectoryfromad extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'adstaff:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import staff from AD';

    protected $fakeous  =   array(  'TestAndServiceUsers',
                                    'Groups',
                                    'WorkPskov',
                                    'SPD',
                                    'GroupsDTSP',
                                    'GroupsDRnP',
                                    'GroupsDPnUS',
                                    'GroupsDMC',
                                    'GroupsDCCP',
                                    'TestForDPT',
                                    'TestForProgrammers');

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

    public function serveDepLevel($dep, $ou) {
        print "\r\n";
        print $dep->getConvertedGuid()  .   "\r\n";
        print $dep->getName()  .   "\r\n";
        print $ou   .   "\r\n";
        $this->serveDepUsers($ou);
        $deps =   Adldap::getProvider('default')->search()->ous()->in($ou .   ",dc=work,dc=kodeks,dc=ru")->listing()->get();
        foreach($deps as $dep_inner) {
            $new_ou =    "OU="    .   $dep_inner->getName()   .   "," .   $ou;
            $this->serveDepLevel($dep_inner,    $new_ou);
        }
    }

    public function serveDepUsers($ou) {
        $users = Adldap::getProvider('default')->search()->users()->in($ou .   ",dc=work,dc=kodeks,dc=ru")->sortBy('samaccountname', 'asc')->listing()->get();
        if(count($users)) {
            foreach($users as $user) {
                print $user->getConvertedSid()  .   "\r\n";
                print $user->getFirstName()  .   "\r\n";
                print $user->getMiddleName() .   "\r\n";
                print $user->getLastName() .   "\r\n";
                print $user->getThumbnail()    .   "\r\n"   .   $user->getJpegPhoto()   .   "\r\n"  .   $user->getUrl() .   "\r\n";
                print $user->getEmail()   .   "\r\n";
                print $user->getDepartment() .   "\r\n";
                print $user->getInfo()   .   "\r\n" .   $user->getDivision()    .   "\r\n";
                print $user->getTelephoneNumber()    .   "\r\n";
                print $user->getPhysicalDeliveryOfficeName() .   "\r\n"    .   $user->getRoomNumber()  .   "\r\n";
                print $user->getTitle() .   "\r\n";

                print "\r\n"    .   "\r\n";
            }
        }
    }
    public function handle()
    {
        //
        $root =   Adldap::getProvider('default')->search()->ous()->find("Консорциум КОДЕКС");
        $this->serveDepLevel($root, "OU=Консорциум КОДЕКС");
    }
}
