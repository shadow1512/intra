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

        /*$rec    =   $client->issue->all([
            'project_id'    =>  Config::get('redmine.project_id_oto'),
            'tracker_id'    =>  Config::get('redmine.tracker_id_oto'),
            'status_id'     =>  'closed',
            //'cf_'   .   Config::get('redmine.cs_room')  =>  '204'
        ]);*/

        /*$rec    =   $client->issue->show(111890);*/

        $issue  =   $client->issue->create([
            'project_id'    => 103,
            'tracker_id'    =>  7,
            'subject' => 'Test Intra api',
            'description' => 'test api',
            'due_date'      =>  date("Y-m-d"),
            'custom_fields' => [
                [
                    'id' => 18,
                    'value' => '205',
                ],
            ],
            'watcher_user_ids' => []
        ]);

        $els    =   $issue->children();

        $issue_id =   null;
        foreach($els as $name   =>  $value) {
            if($name    === "error") {
                Log::error('REDMINE ISSUE CREATION ERROR: ' .   $value);
            }
            if($name    === "id") {
                $issue_id   =   $value;
            }
        }

        if(!is_null($issue_id)) {
            var_dump($issue_id);
        }
        else {

        }
        
    }
}
