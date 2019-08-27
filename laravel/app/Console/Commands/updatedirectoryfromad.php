<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Adldap\Laravel\Facades\Adldap;
use App\User;
use App\Dep;
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
        require_once public_path() . '/hiercode.php';
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */

    public function serveDepLevel($dep, $ou,    $parent_code) {
        $dep=   new Dep();
        $hiercode   =   new \HierCode(CODE_LENGTH);
        if(in_array($dep->getName(),   $this->fakeous)) {
            return;
        }

        //$this->serveDepUsers($ou);
        $deps =   Adldap::getProvider('default')->search()->ous()->in($ou .   ",dc=work,dc=kodeks,dc=ru")->listing()->get();
        $index  =   0;
        foreach($deps as $dep_inner) {
            $parent_id  =   $parent_code;
            if(!null($parent_id)) {
                if($index   ==  0) {
                    for ($i = 0; $i < CODE_LENGTH; $i++) {
                        $parent_id .= $hiercode->digit_to_char[0];
                    }
                }
                else {
                    $parent_id  =   $hiercode->getNextCode();
                }
                $hiercode->setValue($parent_id);
            }
            else {
                $parent_id  =   '';
                if($index   ==  0) {
                    for ($i = 0; $i < CODE_LENGTH; $i++) {
                        $parent_id .= $hiercode->digit_to_char[0];
                    }
                }
                else {
                    $parent_id  =   $hiercode->getNextCode();
                }
                $hiercode->setValue($parent_id);

                $index  ++;
            }
            $dep->parent_id =   $parent_id;
            $dep->name      =   $dep_inner->getName();
            $dep->guid      =   $dep_inner->getConvertedGuid();
            $dep->save();
            $new_ou =    "OU="    .   $dep_inner->getName()   .   "," .   $ou;
            $this->serveDepLevel($dep_inner,    $new_ou,    $dep->parent_id);
        }
    }

    public function serveDepUsers($ou) {
        $users = Adldap::getProvider('default')->search()->users()->in($ou .   ",dc=work,dc=kodeks,dc=ru")->sortBy('samaccountname', 'asc')->listing()->get();
        if(count($users)) {
            foreach($users as $user) {
                if($user->isActive()    &&  $user->isEnabled()) {
                    print $user->getConvertedSid() . "\r\n";
                    print $user->getFirstName() . "\r\n";
                    print $user->getMiddleName() . "\r\n";
                    print $user->getLastName() . "\r\n";
                    print $user->getUrl() . "\r\n";
                    print $user->getEmail() . "\r\n";
                    print $user->getDepartment() . "\r\n";
                    print $user->getDivision() . "\r\n";
                    print $user->getTelephoneNumber() . "\r\n";
                    print $user->getPhysicalDeliveryOfficeName() . "\r\n";
                    print $user->getTitle() . "\r\n";
                    print $user->getBusinessCategory() . "\r\n";

                    print "\r\n" . "\r\n";
                }
            }
        }
    }
    public function handle()
    {
        //

        $root =   Adldap::getProvider('default')->search()->ous()->find("Консорциум КОДЕКС");

        $dep = new Dep();
        $dep->parent_id =   null;
        $dep->name      =   $root->getName();
        $dep->guid      =   $root->getConvertedGuid();
        $dep->save();

        $this->serveDepLevel($root, "OU=Консорциум КОДЕКС", $dep->parent_id);
    }
}
