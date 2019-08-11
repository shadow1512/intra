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
        $client =   new \Redmine\Client(Config::get('redmine.url'), Config::get('redmine.username'), Config::get('redmine.password'));

        $trs =   Technical_Request::whereNull('redmine_link')->get();

        foreach($trs as  $tr) {

            $subject    =   'Заявка на обслуживание из каб. №'   .   $tr->room   .   " от "  .   $tr->fio;
            if($tr->phone) {
                $subject    .=  ", (тел.:"  .   $tr->phone  .   ")";
            }

            $description    =   $subject    .   "\r\n";
            if($tr->type_request    ==  "cartridge") {
                $description    .=  "Тип заявки: замена картриджа\r\n";
                $description    .=  "Модель принтера: " .   $tr->printer    .   "\r\n";
            }
            if($tr->type_request    ==  "teh") {
                $description    .=  "Тип заявки: техническое обслуживание\r\n";
            }

            $description    .=   $tr->user_comment    .   "\r\n";
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

            if(is_null($issue)) {
                Log::error('REDMINE ISSUE CREATION ERROR: no issue  for record ' .   $tr->id);
                return;
            }
            $els    =   $issue->children();

            $issue_id =   null;
            foreach($els as $name   =>  $value) {
                if($name    === "error") {
                    Log::error('REDMINE ISSUE CREATION ERROR: ' .   $value  .   " for record " .   $tr->id);
                    return;
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
