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

        $rec    =   $client->issue->all([
            'project_id'    =>  Config::get('redmine.project_id_oto'),
            'tracker_id'    =>  Config::get('redmine.tracker_id_oto'),
            'status_id'     =>  'closed',
            'cf_'   .   Config::get('redmine.cs_room')  =>  '204'
        ]);

        var_dump($rec);

        $rec    =   $client->issue->all([
            'project_id'    =>  Config::get('redmine.project_id_oto'),
            'tracker_id'    =>  Config::get('redmine.tracker_id_oto'),
            'status_id'     =>  'closed',
            'cf_'   .   Config::get('redmine.cs_room')  =>  '203'
        ]);

        var_dump($rec);

        $rec    =   $client->issue->all([
            'project_id'    =>  Config::get('redmine.project_id_oto'),
            'tracker_id'    =>  Config::get('redmine.tracker_id_oto'),
            'status_id'     =>  'closed',
            'cf_'   .   Config::get('redmine.cs_room')  =>  '202'
        ]);

        var_dump($rec);
    }
}
