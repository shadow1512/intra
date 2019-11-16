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

class makeXmlToUpdateAD extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'adxml:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate XML file for every updated user last day';

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
    public function handle()
    {
        //
        $caldate = new DateTime();
        $finish  =   $caldate->format("Y-m-d H:i:s");
        $caldate->sub(new DateInterval("P1D"));
        $start =   $caldate->format("Y-m-d H:i:s");

        $up_users   =   User::select("users.*", "deps_peoples.work_title", "deps_peoples.chef", "deps.name as depname",    "deps.parent_id")
            ->leftJoin("deps_peoples",  "users.id", "=",    "deps_peoples.people_id")
            ->leftJoin("deps",  "deps.id",  "=",    "deps_peoples.dep_id")
            ->whereBetween("users.updated_at",    [$start, $finish])->get();
        if($up_users->count()) {

            $bar = $this->output->createProgressBar(count($up_users));

            if(!Storage::disk('public')->exists('/xml/export/'    .   $caldate->format("Ymd")   .   '/')) {
                Storage::disk('public')->makeDirectory('/xml/export/' . $caldate->format("Ymd") . '/');
            }
            foreach($up_users as $user) {
                $parent_id  =   mb_substr($user->parent_id, 0,  2,  "UTF-8");
                $department_name   =   Dep::where("parent_id", "=",    $parent_id)->value("short_name");

                $dom = new DOMDocument('1.0', 'utf-8');
                $data       =   $dom->createElement("data");
                $datanode   =   $dom->appendChild($data);
                $userel     =   $dom->createElement("user");
                $usernode   =   $datanode->appendChild($userel);
                $sid        =   $dom->createElement("sid",  $user->sid);
                $sidnode    =   $usernode->appendChild($sid);

                $fname      =   $dom->createElement("fname",  preg_replace("/[^А-яЁё  \-]/ius",   "", $user->fname));
                $fnamenode  =   $usernode->appendChild($fname);
                $mname      =   $dom->createElement("mname",  preg_replace("/[^А-яЁё  \-]/ius",   "", $user->mname));
                $mnamenode  =   $usernode->appendChild($mname);
                $lname      =   $dom->createElement("lname",  preg_replace("/[^А-яЁё  \-]/ius",   "", $user->lname));
                $lnamenode  =   $usernode->appendChild($lname);
                $birthday       =   $dom->createElement("birthday",  $user->birthday);
                $birthdaynode   =   $usernode->appendChild($birthday);

                $phones         =   $dom->createElement("phones");
                $phonesnode     =   $usernode->appendChild($phones);
                $localphone     =   $dom->createElement("localphone", preg_replace("/[^0-9]/ius",   "", $user->phone));
                $localphonenode =   $phonesnode->appendChild($localphone);
                $mobilephone     =   $dom->createElement("mobilephone", preg_replace("/[^0-9]/ius",   "", $user->mobile_phone));
                $mobilephonenode =   $phonesnode->appendChild($mobilephone);

                $address        =   $dom->createElement("address");
                $addressnode    =   $usernode->appendChild($address);
                $room           =   $dom->createElement("room", preg_replace("/[^0-9]/ius",   "", $user->room));
                $roomnode       =   $addressnode->appendChild($room);

                $email          =   $dom->createElement("emails",   $user->email);
                $emailnode      =   $usernode->appendChild($email);

                $work           =   $dom->createElement("work");
                $worknode       =   $usernode->appendChild($work);
                $worktitle      =   $dom->createElement("worktitle", $user->work_title);
                $worktitlenode  =   $worknode->appendChild($worktitle);
                if($user->chef  >=   2) {
                    $user->chef =   "boss";
                }
                else if($user->chef  ==   1) {
                    $user->chef =   "superboss";
                }
                else if(is_null($user->chef)) {
                    $user->chef =   "";
                }
                else {
                    $user->chef =   "president";
                }
                $businesscat    =   $dom->createElement("businessCategory", $user->chef);
                $businesscatnode=   $worknode->appendChild($businesscat);
                $division       =   $dom->createElement("division", $user->depname);
                $divisionnode   =   $worknode->appendChild($division);
                $department     =   $dom->createElement("department", $department_name);
                $departmentnode =   $worknode->appendChild($department);

                $desc           =   $dom->createElement("description",    $user->position_desc);
                $descnode       =   $usernode->appendChild($desc);

                $photo          =   $dom->createElement("photopath",  $user->avatar);
                $photonode      =   $usernode->appendChild($photo);

                Storage::disk('public')->put('/xml/export/'    .   $caldate->format("Ymd")   .   '/'    .   $user->id   .   '.xml', $dom->saveXML(), 'public');

                $bar->advance();
            }
            $bar->finish();
        }
    }
}
