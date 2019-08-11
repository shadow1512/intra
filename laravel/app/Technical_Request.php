<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use kbsali\Redmine\Client;
use Config;

class Technical_Request extends Model
{
    //
    protected $table = 'technical_requests';

    public function syncToRedmine() {
        $client =   new \Redmine\Client(Config::get('redmine.url'), Config::get('redmine.username'), Config::get('redmine.password'));

        /*$rec    =   $client->issue->all([
            'project_id'    =>  Config::get('redmine.project_id_oto'),
            'tracker_id'    =>  Config::get('redmine.tracker_id_oto'),
            'status_id'     =>  'closed',
            //'cf_'   .   Config::get('redmine.cs_room')  =>  '204'
        ]);*/

        /*$rec    =   $client->issue->show(111890);*/

        var_dump($client->issue->create([
            'project_id'    => 103,
            'tracker_id'    =>  7,
            'subject' => 'Test Intra api',
            'description' => 'test api',
            'due_date'      =>  date("Y-m-d"),
            'custom_fields' => [
                [
                    'id' => 17,
                    'value' => '2.9 Департамент программных технологий',
                ],
                [
                    'id' => 18,
                    'value' => '205',
                ],
            ],
            'watcher_user_ids' => []
        ]));
        //var_dump($rec);
    }
}
