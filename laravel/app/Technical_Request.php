<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use kbsali\Redmine\Client;
use Config;
use App\User;

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

            $issue= null;
            if($tr->type_request    ==  "cartridge") {
                $issue = $client->issue->create([
                    'project_id'        =>  110,
                    'tracker_id'        =>  6,
                    //'assigned_to_id'    =>  778,
                    'subject' => $subject,
                    'description' => $description,
                    'due_date' => date("Y-m-d"),
                    'custom_fields' => [
                        [
                            'id' => 18,
                            'value' => $tr->room,
                        ],
                        [
                            'id' => 14,
                            'value' => $tr->printer,
                        ],
                    ],
                    'watcher_user_ids' => []
                ]);
            }
            else {
                $issue = $client->issue->create([
                    'project_id' => 103,
                    'tracker_id' => 7,
                    'subject' => $subject,
                    'description' => $description,
                    'due_date' => date("Y-m-d"),
                    'custom_fields' => [
                        [
                            'id' => 18,
                            'value' => $tr->room,
                        ],
                    ],
                    'watcher_user_ids' => []
                ]);
            }

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
    }

    public function syncFromRedmine() {
        $client =   new \Redmine\Client(Config::get('redmine.url'), Config::get('redmine.username'), Config::get('redmine.password'));
        $trs =   Technical_Request::whereNotNull('redmine_link')->where(function($query) {
            $query->whereNull('status')->orWhere('status',  '=',    'inprogress');
        })->get();

        //$rec    =   $client->issue->show(112993);var_dump($rec);die();
        //$assigned   =   $client->user->show(37);var_dump($assigned);die();
        foreach($trs as  $tr) {
            //echo $tr->redmine_link . "\r\n";
            $rec    =   $client->issue->show($tr->redmine_link);
            if($rec) {
                //var_dump($rec);
                if(isset($rec["issue"]["status"]["id"])) {
                    $status =   $rec["issue"]["status"]["id"];
                    if($status  ==  1) {
                        $tr->status =   null;
                    }
                    elseif($status  ==  3   ||  $status  ==  5) {
                        $tr->status =   "complete";
                    }
                    elseif($status  ==  6) {
                        $tr->status =   "rejected";
                    }
                    else {
                        $tr->status =   "inprogress";
                    }
                }
                else {
                    Log::error('REDMINE ISSUE STATUS ERROR: no status  for record ' .   $tr->redmine_link);
                }
                if(isset($rec["issue"]["assigned_to"]["id"])) {
                    $assigned   =   $client->user->show($rec["issue"]["assigned_to"]["id"]);
                    if($assigned    &&  isset($assigned["user"]["mail"])) {
                        $user   =   User::where('email',    '=', trim($assigned["user"]["mail"]))->first();
                        if($user) {
                            $tr->assigned =   $user->id;
                        }
                        else {
                            $tr->assigned_text =   $rec["issue"]["assigned_to"]["name"];
                        }
                    }
                    else {
                        $tr->assigned_text =   $rec["issue"]["assigned_to"]["name"];
                    }
                }

                $tr->save();
            }
            else {
                Log::error('REDMINE ISSUE SEARCH ERROR: no issue  for record ' .   $tr->redmine_link);
                //return;
            }
        }
    }
}
