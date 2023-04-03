<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;
use Config;

class Booking extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'room_id', 'user_id', 'date_book', 'time_start', 'time_end'
    ];

    protected $table = 'room_bookings';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

    public function syncToRedmine() {
        $client =   new \Redmine\Client(Config::get('redmine.url'), Config::get('redmine.username'), Config::get('redmine.password'));

        $trs =   Booking::select("room_bookings.*", "rooms.name as room_name",  "users.phone as user_phone",    "users.fname",   "users.mname",  "users.lname")
            ->leftJoin('rooms', 'rooms.id', '=',    'room_bookings.room_id')
            ->leftJoin('users', 'users.id', '=',    'room_bookings.user_id')
            ->whereNull('redmine_link')->where(function($query) {
            $query->where('notebook_own',   '=',    1)->orWhere('notebook_ukot',   '=',    1)->orWhere('info_internet',   '=',    1)->orWhere('info_kodeks',   '=',    1)
                ->orWhere('software_skype',   '=',    1)    ->orWhere('software_ukotman',   '=',    1)->orWhere('software_skype_for_business',   '=',    1)->orWhere('type_meeting_webinar',   '=',    1)->orWhere('type_meeting_other',   '=',    1)
                ->orWhereNotNull('notes');
        })->get();

        foreach($trs as  $tr) {
            $subject    =   'Подготовка переговорной №'   .   $tr->room_name   .   " для мероприятия "  .   $tr->name;

            $description    =   $subject    .   "\r\n";
            $description    .=  "Организатор: " .   $tr->lname  .   " " .   $tr->fname  .   " " .   $tr->mname;
            if($tr->user_phone) {
                $description    .=  " (тел.: " .   $tr->user_phone    .   ")";
            }
            $description    .=  "\r\n";

            $description    .=  "Дата: " .   $tr->date_book  .   "\r\n";
            $description    .=  "Время: " .   $tr->time_start  .   " - "    .   $tr->time_end   .   "\r\n";
            $description    .=  "Отмеченные потребности:\r\n";

            if($tr->notebook_own) {
                $description    .=  "Собственный ноутбук\r\n";
            }
            if($tr->notebook_ukot) {
                $description    .=  "Ноутбук УКОТ\r\n";
            }
            if($tr->info_internet) {
                $description    .=  "Доступ в Интернет\r\n";
            }
            if($tr->info_kodeks) {
                $description    .=  "Доступ к информационным продуктам \"Кодекс\"\r\n";
            }
            if($tr->software_skype) {
                $description    .=  "Skype\r\n";
            }
            if($tr->software_ukotman) {
				$description    .=  "Требуется присутствие специалиста УКОТ на мероприятии\r\n";
			}
            if($tr->software_skype_for_business) {
                $description    .=  "Skype for Business\r\n";
            }
            if($tr->type_meeting_webinar) {
                $description    .=  "Тип мероприятия - вебинар\r\n";
            }
            if($tr->type_meeting_other) {
                $description    .=  "Тип мероприятия - иной\r\n";
            }
            if($tr->notes) {
                $description    .=  "Комментарии организатора: "    .   $tr->notes  .   "\r\n";
            }

            $issue  =   $client->issue->create([
                'project_id'    => 103,
                'tracker_id'    =>  7,
                'subject'       => $subject,
                'description'   => $description,
                'due_date'      =>  date("Y-m-d"),
                'custom_fields' => [
                    [
                        'id' => 18,
                        'value' => $tr->room_name,
                    ],
                ],
                'watcher_user_ids' => []
            ]);

            if(is_null($issue)) {
                Log::error('REDMINE ISSUE CREATION ERROR: no issue  for room record ' .   $tr->id);
                return;
            }
            $els    =   $issue->children();

            $issue_id =   null;
            foreach($els as $name   =>  $value) {
                if($name    === "error") {
                    Log::error('REDMINE ISSUE CREATION ERROR: ' .   $value  .   " for room record " .   $tr->id);
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
}