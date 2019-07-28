<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\Dep;
use Illuminate\Support\Facades\Storage;
use DateTime;
use DateInterval;
use DOMDocument;
use Config;

class makeXmlToCreateADStructure extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'adstructurexml:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create XML with directory structure to copy to AD';

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
    public function handle()
    {
        //
    }

    private function getLevel($code =   null,   $code_length    =   CODE_LENGTH,    $dom =   null,  $node   =   null) {

        if(is_null($dom)) {
            $dom = new DOMDocument('1.0', 'utf-8');

            $struct         =   $dom->createElement("structure");
            $structnode     =   $dom->appendChild($struct);
            $root           =   $dom->createElement("root");
            $rootnode       =   $structnode->appendChild($root);
            $name           =   $dom->createElement("name", "Консорциум \"КОДЕКС\"");
            $namenode       =   $rootnode->appendChild($name);
            $node           =   $rootnode;
        }

        if(is_null($code)) {
            $deps   =   Dep::whereRaw("LENGTH(parent_id)="  .   CODE_LENGTH)->get();
        }
        else {
            $deps   =   Dep::where('parent_id', 'LIKE', $code.  "%")->whereRaw("LENGTH(parent_id)="  .   $code_length)->get();
        }

        if(count($deps)) {
            $children           =   $dom->createElement("children");
            $childrennode       =   $node->appendChild($children);

            foreach($deps as $dep) {

                $department             =   $dom->createElement("department");
                $employeenode           =   $childrennode->appendChild($department);

                $name           =   $dom->createElement("name", $dep->name);
                $namenode       =   $departmentnode->appendChild($name);

                $users  =   User::select("users.*", "deps_peoples.work_title",  "deps.name as depname",    "deps.parent_id",   "user_keys.sid",    "user_keys.user_login")
                    ->leftJoin("deps_peoples",  "users.id", "=",    "deps_peoples.people_id")
                    ->leftJoin("user_keys",  "users.id",  "=",    "user_keys.user_id")
                    ->whereRaw("deps_peoples.dep_id="   .   $dep->id)->get();
                if(count($users)) {
                    $people             =   $dom->createElement("people");
                    $peoplenode         =   $departmentnode->appendChild($people);
                    foreach($users as $user) {
                        $employee             =   $dom->createElement("employee");
                        $employeenode         =   $peoplenode->appendChild($employee);

                        $sid        =   $dom->createElement("sid",  $user->sid);
                        $sidnode    =   $employeenode->appendChild($sid);

                        $fname      =   $dom->createElement("fname",  preg_replace("/[^А-яЁё  \-]/ius",   "", $user->fname));
                        $fnamenode  =   $employeenode->appendChild($fname);
                        $mname      =   $dom->createElement("mname",  preg_replace("/[^А-яЁё  \-]/ius",   "", $user->mname));
                        $mnamenode  =   $employeenode->appendChild($mname);
                        $lname      =   $dom->createElement("lname",  preg_replace("/[^А-яЁё  \-]/ius",   "", $user->lname));
                        $lnamenode  =   $employeenode->appendChild($lname);

                        $email          =   $dom->createElement("emails",   $user->email);
                        $emailnode      =   $employeenode->appendChild($email);
                    }
                }

                $this->getLevel($dep->parent_id,    mb_strlen($dep->parent_id,  "UTF-8")    +   CODE_LENGTH,    $dom,   $departmentnode);
            }
        }

        if(is_null($code)) {
            Storage::disk('public')->put('/xml/export/struct.xml', $dom->saveXML(), 'public');
        }
    }
}
