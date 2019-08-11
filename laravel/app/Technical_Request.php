<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use kbsali\Redmine\Client;
use Config;

class Technical_Request extends Model
{
    //
    protected $table = 'technical_requests';

    public function syncToRedmine() {
        $client =   new \Redmine\Client(Config::get('redmine.url'), Config::get('redmine.username'), Config::get('redmine.password1'));
        
        $trs =   Technical_Request::whereNull('redmine_link')->get();

        foreach($trs as  $tr) {

            $subject    =   'Заявка на обслуживание из каб. №'   .   $tr->room   .   " от "  .   $tr->fio;
            if($tr->phone) {
                $subject    .=  ", (тел.:"  .   $tr->phone;
            }

            $description    =   $subject    .   "<br/>";
            if($tr->type    ==  "cartridge") {
                $description    .=  "Тип заявки: замена картриджа<br/>";
                $description    .=  "Модель принтера: " .   $tr->printer    .   "<br/>";
            }
            if($tr->type    ==  "teh") {
                $description    .=  "Тип заявки: техническое обслуживание<br/>";
            }

            $description    .=   $ек->user_comments    .   "<br/>";
            $description    .=  "Подразделение: "   .   $tr->dep;

            $issue  =   $client->issue->create([
                'project_id'    => 103,
                'tracker_id'    =>  7,
                'subject'       => $subject,
                'description'   => $description,
                'due_date'      =>  date("Y-m-d"),
                'custom_fields' => [
                    [
                        'id' => 18,
                        'value' => $tr->room,
                    ],
                ],
                'watcher_user_ids' => []
            ]);

            $els    =   $issue->children();

            $issue_id =   null;
            foreach($els as $name   =>  $value) {
                if($name    === "error") {
                    Log::error('REDMINE ISSUE CREATION ERROR: ' .   $value  .   " for record " .   $tr->id);
                }
                if($name    === "id") {
                    $issue_id   =   $value;
                }
            }

            if(!is_null($issue_id)) {
                $tr->redmine_link   =   $issue_id->__toString();
                $tr->save();
            }
            else {

            }
        }
        /*$rec    =   $client->issue->all([
            'project_id'    =>  Config::get('redmine.project_id_oto'),
            'tracker_id'    =>  Config::get('redmine.tracker_id_oto'),
            'status_id'     =>  'closed',
            //'cf_'   .   Config::get('redmine.cs_room')  =>  '204'
        ]);*/

        /*$rec    =   $client->issue->show(111890);*/
    }
}
