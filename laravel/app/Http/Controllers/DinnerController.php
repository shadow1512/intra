<?php
/**
 * Created by PhpStorm.
 * User: viach
 * Date: 27.05.2020
 * Time: 1:05
 */

namespace App\Http\Controllers;

use App\Dinner_booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Config;
use Auth;
use View;

class DinnerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

    }

    public function book()
    {
        $caldate = Carbon::now();

        $bookings = Dinner_booking::selectRaw('COUNT(id) as num_records, time_start')
            ->whereDate('date_created', '=',    $caldate->toDateString())
            ->groupBy('time_start')
            ->orderBy('time_start')
            ->get();

        $bookings_by_times = array();
        foreach($bookings as $booking) {
            $bookings_by_times[$booking->time_start] = $booking['num_records'];
        }

        var_dump($bookings_by_times);
        return view('kitchen.book', [   'periods'           =>  Config::get('dinner.dinner_slots'),
                                        'bookings'          =>  $bookings_by_times,
                                        'total_accepted'    =>  Config::get('dinner.total_accepted'),
                                        'kitchen_booking'   =>  Dinner_booking::getRecordByUserAndDate(Auth::user()->id, $caldate->toDateString())]);
    }

    public function createbooking(Request $request) {
        $time_start          = trim($request->input('time_start'));

        $messages   =   array(      "time_start.required"       =>  "Поле обязательно для заполнения",
                                    "time_start.date_format"    =>  "Время должно быть в формате ЧЧ:ММ",
        );

        $validator = Validator::make($request->all(), [
            'time_start'      => 'required|date_format:H:i',
        ],  $messages);

        if ($validator->fails()) {
            return response()->json(['error', $validator->errors()]);
        }
        else {
            if(!in_array($time_start,   Config::get('dinner.dinner_slots'))) {
                return response()->json(['error',  'message' =>  'wrong time']);
            }

            $caldate = Carbon::now();

            //Нужно проверить, что не переполнилась запись
            $num_bookings =   Dinner_booking::whereDate('date_created',    $caldate->toDateString())->whereTime("time_start",  "=",    $time_start)->count();
            if($num_bookings    >   Config::get('dinner.total_accepted')) {
                return response()->json(['error',  'message' =>  'no places']);
            }
            //Не записались ли мы случаем уже

            $exist = Dinner_booking::whereDate('date_created', '=', $caldate->toDateString())->where("user_id", "=", Auth::user()->id)->exists();
            if ($exist) {
                return response()->json(['error', 'message' => 'already booked']);
            }

            $caldate = Carbon::createFromFormat("Y-m-d H:i",    $caldate->toDateString()    .   " " .   $time_start);
            $booking    =   new Dinner_booking();
            $booking->user_id           =   Auth::user()->id;
            $booking->date_created      =   $caldate->toDateString();
            $booking->time_start        =   $time_start;

            $caldate->addMinutes(15);
            $booking->time_end          =   $caldate->format("h:i");
            $booking->save();

            return response()->json(['result' => 'success']);
        }
    }

    /*public function viewbooking($id) {
        $booking =  Booking::findOrFail($id);
        $rooms  =   Rooms::orderBy('name')->get();

        if (Auth::check()) {
            if($booking->user_id    ==  Auth::user()->id) {
                $html   =   View::make('rooms.change', ['booking'   =>  $booking,   'rooms' =>  $rooms]);
                return response()->json(['result'   =>  'success',  'html'  =>  $html->render()]);
            }
            else {
                return response()->json(['result'   =>  'error',    'text'  =>  'Вы не можете изменять это бронирование, т.к. не вы его создавали']);
            }
        }
        else {
            return response()->json(['result'   =>  'error',    'text'  =>  'Вы не можете изменять это бронирование, т.к. вы не авторизованы']);
        }

    }

    public function savebooking($id, Request $request) {
        $name           =   trim($request->input('input_name_change'));
        $date_booking   =   trim($request->input('input_date_booking_change'));
        $time_start     =   trim($request->input('input_time_start_change'));
        $time_end       =   trim($request->input('input_time_end_change'));
        $room           =   trim($request->input('input_room'));

        $notebook_own   =   $request->input('notebook_own_change');
        if(is_null($notebook_own)) {
            $notebook_own   =   0;
        }

        $notebook_ukot   =   $request->input('notebook_ukot_change');
        if(is_null($notebook_ukot)) {
            $notebook_ukot   =   0;
        }

        $info_internet   =   $request->input('info_internet_change');
        if(is_null($info_internet)) {
            $info_internet   =   0;
        }

        $info_kodeks   =   $request->input('info_kodeks_change');
        if(is_null($info_kodeks)) {
            $info_kodeks   =   0;
        }

        $software_skype   =   $request->input('software_skype_change');
        if(is_null($software_skype)) {
            $software_skype   =   0;
        }

        $software_skype_for_business   =   $request->input('software_skype_for_business_change');
        if(is_null($software_skype_for_business)) {
            $software_skype_for_business   =   0;
        }

        $type_meeting_webinar   =   $request->input('type_meeting_webinar_change');
        if(is_null($type_meeting_webinar)) {
            $type_meeting_webinar   =   0;
        }

        $type_meeting_other   =   $request->input('type_meeting_other_change');
        if(is_null($type_meeting_other)) {
            $type_meeting_other   =   0;
        }

        $notes          = trim($request->input('notes_change'));

        $messages   =   array(  "input_name_change.required"           =>  "Поле \"название мероприятия\" обязательно для заполнения",
            "input_name_change.max"                 =>  "Поле \"название мероприятия\" не должно быть длиннее, чем 90 символов",
            "input_date_booking_change.required"    =>  "Поле \"дата бронирования\" обязательно для заполнения",
            "input_time_start_change.required"      =>  "Поле \"время начала\" обязательно для заполнения",
            "input_time_end_change.required"        =>  "Поле \"время окончания\" обязательно для заполнения",
            "input_time_start_change.date_format"   =>  "Время начала бронирования должно быть в формате ЧЧ:ММ",
            "input_time_end_change.date_format"     =>  "Время окончания бронирования должно быть в формате ЧЧ:ММ",
            "input_room.required"                   =>  "Поле \"комната\" обязательно для заполнения",
            "notes_change.max"                      =>  "Поле \"примечание\" не должно быть длиннее 255 символов"
        );

        $validator = Validator::make($request->all(), [
            'input_name_change'            => 'required|max:90',
            'input_date_booking_change'    => 'required',
            'input_time_start_change'      => 'required|date_format:H:i',
            'input_time_end_change'        => 'required|date_format:H:i',
            'input_room'                   => 'required',
        ],  $messages);

        if ($validator->fails()) {
            return response()->json(['error', $validator->errors()]);
        }
        else {

            if($time_start  <   Config::get('rooms.time_start_default')) {
                return response()->json(['error',  'message' =>  'time start too early',    'field' =>  'input_time_start_change']);
            }
            if($time_end  >   Config::get('rooms.time_end_default')) {
                return response()->json(['error',  'message' =>  'time end too late',    'field' =>  'input_time_end_change']);
            }
            //Нужно проверить, что не перекрывается по датам
            $exists =   Booking::where("id",    "<>",    $id)
                ->where("room_id",  "=",    $room)
                ->whereDate('date_book',    $date_booking)
                ->where(function($query) use ($time_start,  $time_end) {
                    $query->whereBetween('time_start',  [$time_start,   $time_end])->orWhereBetween('time_end', [$time_start,   $time_end])
                        ->orWhere(function($query) use ($time_start,  $time_end) {
                            $query->whereTime('time_start', '<',    $time_start)->whereTime('time_end', '>',    $time_end);
                        })
                        ->orWhere(function($query) use ($time_start,  $time_end) {
                            $query->whereTime('time_start', '>',    $time_start)->whereTime('time_end', '<',    $time_end);
                        });
                })->exists();

            if($exists) {
                return response()->json(['error',  'message' =>  'crossing detected']);
            }
            else {
                $date = date("Y-m-d H:i:s");
                if (Auth::check()) {
                    $booking    =   Booking::findOrFail($id);
                    $room       =   Rooms::findOrFail($room);
                    if($room->available) {
                        $booking->approved   =   1;
                    }
                    else {
                        $booking->approved   =   0;
                    }
                    $booking->room_id       =   $room->id;
                    $booking->name          =   $name;
                    $booking->date_book     =   $date_booking;
                    $booking->time_start    =   $time_start;
                    $booking->time_end      =   $time_end;
                    $booking->updated_at    =   $date;
                    $booking->notebook_own  =   $notebook_own;
                    $booking->notebook_ukot =   $notebook_ukot;
                    $booking->info_internet =   $info_internet;
                    $booking->info_kodeks   =   $info_kodeks;
                    $booking->software_skype                =   $software_skype;
                    $booking->software_skype_for_business   =   $software_skype_for_business;
                    $booking->type_meeting_webinar  =   $type_meeting_webinar;
                    $booking->type_meeting_other    =   $type_meeting_other;
                    $booking->notes =   $notes;
                    $booking->save();

                    if(!$booking->approved  &&  $room->notify_email) {
                        Mail::send('emails.editbooking', ['booking' => $booking, 'user'  =>  Auth::user(),  'room'  =>  $room], function ($m) use ($room) {
                            $m->from('newintra@kodeks.ru', 'Новый корпоративный портал');
                            $m->to($room->notify_email)->subject('Изменено бронирование в переговорной '    .   $room->name);
                        });
                    }

                    return response()->json(['result' => 'success']);
                }
            }
        }
    }*/
}
