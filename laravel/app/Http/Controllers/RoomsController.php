<?php

namespace App\Http\Controllers;

use App\Rooms;
use App\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DateTime;
use DateInterval;
use DB;
use Config;
use Auth;
use View;
use Mail;

class RoomsController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
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

    public function book($id,   $dir    =   null,   $num    =   0)
    {
        $room = Rooms::findOrFail($id);
        //

        $caldate = new DateTime();
        if(!is_null($dir)) {
            if($dir ==  "prev"  &&  $num    >   0) {
                $caldate->sub(new DateInterval("P"  .   $num    .   "W"));
            }
            if($dir ==  "next"  &&  $num    >   0) {
                $caldate->add(new DateInterval("P"  .   $num    .   "W"));
            }
        }
        $curweekday = $caldate->format("N");

        $subperiod  = 0;
        if($curweekday  > 1) {
            $subperiod  = $curweekday - 1;
        }
        if($subperiod) {
            $caldate = $caldate->sub(new DateInterval("P" . $subperiod . "D"));
        }

        $startdate    =   $caldate->format("Y-m-d");
        $caldate->add(new DateInterval("P5D"));

        $bookings = Booking::select('room_bookings.*',
                                            'users.name as person_name', 'users.phone as person_phone', 'users.email as person_email', 'users.fname as fname',  'users.lname as lname', 'users.avatar as avatar')
            ->selectRaw('TIMESTAMPDIFF(MINUTE,  room_bookings.time_start,   room_bookings.time_end) as duration')
            ->leftJoin("users", 'room_bookings.user_id', '=', 'users.id')
            ->whereBetween('date_book', [$startdate,  $caldate->format("Y-m-d") .   " 23:59:59"])
            ->where('room_id',  '=',    $id)
            ->orderBy('date_book')
            ->orderBy('time_start')
            ->get();

        $bookings_by_dates = array();
        foreach($bookings as $booking) {
            $bookings_by_dates[strtotime($booking->date_book)][] = $booking;
        }

        return view('rooms.order', ['room'    =>  $room, 'bookings'   =>  $bookings_by_dates,  'dir'   =>  $dir,   'num'   =>  $num]);
    }

    public function createbooking($id, Request $request) {
        $name          = trim($request->input('input_name'));
        $date_booking  = trim($request->input('input_date_booking'));
        $time_start    = trim($request->input('input_time_start'));
        $time_end      = trim($request->input('input_time_end'));
        
        //По требованию УКОТ меняем форму, все старые отметки не нужны (убрали из формы), нужна только одна галочка - если она есть, то нужно еще примечание
        $notebook_own   =   $request->input('notebook_own');
        if(is_null($notebook_own)) {
            $notebook_own   =   0;
        }

        $notebook_ukot   =   $request->input('notebook_ukot');
        if(is_null($notebook_ukot)) {
            $notebook_ukot   =   0;
        }

        $info_internet   =   $request->input('info_internet');
        if(is_null($info_internet)) {
            $info_internet   =   0;
        }

        $info_kodeks   =   $request->input('info_kodeks');
        if(is_null($info_kodeks)) {
            $info_kodeks   =   0;
        }

        $software_skype   =   $request->input('software_skype');
        if(is_null($software_skype)) {
            $software_skype   =   0;
        }

        $software_skype_for_business   =   $request->input('software_skype_for_business');
        if(is_null($software_skype_for_business)) {
            $software_skype_for_business   =   0;
        }

        $type_meeting_webinar   =   $request->input('type_meeting_webinar');
        if(is_null($type_meeting_webinar)) {
            $type_meeting_webinar   =   0;
        }

        $type_meeting_other   =   $request->input('type_meeting_other');
        if(is_null($type_meeting_other)) {
            $type_meeting_other   =   0;
        }
        
        $ukot_presence   =   $request->input('ukot_presence');
        if(is_null($ukot_presence)) {
            $ukot_presence   =   0;
        }
        $notes          = trim($request->input('notes'));
        
        $aho_presence   =   $request->input('aho_presence');
        if(is_null($aho_presence)) {
            $aho_presence   =   0;
        }

        $messages   =   array(  "input_name.required"           =>  "Поле обязательно для заполнения",
                                "input_name.max"                =>  "Поле не должно быть длиннее, чем 90 символов",
                                "input_date_booking.required"   =>  "Поле обязательно для заполнения",
                                "input_time_start.required"     =>  "Поле обязательно для заполнения",
                                "input_time_end.required"       =>  "Поле обязательно для заполнения",
                                "input_time_start.date_format"  =>  "Время должно быть в формате ЧЧ:ММ",
                                "input_time_end.date_format"    =>  "Время должно быть в формате ЧЧ:ММ",
                                "notes.max"                     =>  "Поле не должно быть длиннее, чем 255 символов"
        );

        $validator = Validator::make($request->all(), [
            'input_name'            => 'required|max:90',
            'input_date_booking'    => 'required',
            'input_time_start'      => 'required|date_format:H:i',
            'input_time_end'        => 'required|date_format:H:i',
            'notes'                 => 'nullable|max:255',
        ],  $messages);

        if ($validator->fails()) {
            return response()->json(['error', $validator->errors()]);
        }
        else {
            if($ukot_presence) {
                if(!mb_strlen($notes,    "UTF-8")) {
                    return response()->json(['error',  'message' =>  'notes required for ukot',    'field' =>  'notes']);
                }
            }
            if($aho_presence) {
                $caldate    =   new Date();
                $service_date   =   $caldate->add(new DateInterval("P1D"));
                if($date_booking < $service_date) {
                    return response()->json(['error',  'message' =>  'correct interval for aho',    'field' =>  'input_date_booking']);
                }
            }
            if($time_start  <   Config::get('rooms.time_start_default')) {
                return response()->json(['error',  'message' =>  'time start too early',    'field' =>  'input_time_start']);
            }
            if($time_end  >   Config::get('rooms.time_end_default')) {
                return response()->json(['error',  'message' =>  'time end too late',    'field' =>  'input_time_end']);
            }
            //Нужно проверить, что не перекрывается по датам
            //DB::enableQueryLog();
            $exists =   Booking::whereDate('date_book',    $date_booking)
                            ->where("room_id",  "=",    $id)
                             ->where(function($query) use ($time_start,  $time_end) {
                                        $query->whereBetween('time_start',  [$time_start,   $time_end])->orWhereBetween('time_end', [$time_start,   $time_end])
                                         ->orWhere(function($query) use ($time_start,  $time_end) {
                                             $query->whereTime('time_start', '<',    $time_start)->whereTime('time_end', '>',    $time_end);
                                         })
                                        ->orWhere(function($query) use ($time_start,  $time_end) {
                                            $query->whereTime('time_start', '>', $time_start)->whereTime('time_end', '<', $time_end);
                                        });
                                        })->exists();
            //print_r(DB::getQueryLog());exit();

            if($exists) {
                return response()->json(['error',  'message' =>  'crossing detected']);
            }
            else {
                $date = date("Y-m-d H:i:s");
                if (Auth::check()) {
                    $approved   =   0;
                    $room = Rooms::findOrFail($id);
                    if($room->available) {
                        $approved   =   1;
                    }

                    $booking    =   new Booking();
                    $booking->room_id   =   $id;
                    $booking->name      =   $name;
                    $booking->date_book =   $date_booking;
                    $booking->user_id   =   Auth::user()->id;
                    $booking->time_start    =   $time_start;
                    $booking->time_end      =   $time_end;
                    $booking->approved  =   $approved;
                    $booking->notebook_own      =   $notebook_own;
                    $booking->notebook_ukot     =   $notebook_ukot;
                    $booking->info_internet     =   $info_internet;
                    $booking->info_kodeks       =   $info_kodeks;
                    $booking->software_skype    =   $software_skype;
                    $booking->software_skype_for_business    =   $software_skype_for_business;
                    $booking->type_meeting_webinar           =   $type_meeting_webinar;
                    $booking->type_meeting_other             =   $type_meeting_other;
                    $booking->service_ukot                   =   $ukot_presence;
                    $booking->service_aho                    =   $aho_presence;
                    $booking->notes             =   $notes;

                    $booking->save();

                    if(!$booking->approved  &&  $room->notify_email) {
                        $emails =   explode(",", $room->notify_email);
                        foreach($emails as $email) {
                            Mail::send('emails.newbooking', ['booking' => $booking, 'user'  =>  Auth::user(),  'room'  =>  $room], function ($m) use ($room) {
                                $m->from('newintra@kodeks.ru', 'Новый корпоративный портал');
                                $m->to($email)->subject('Новое бронирование в переговорной '    .   $room->name);
                            });
                        }
                    }
                    if($booking->service_aho    &&  $room->notify_email) {
                        $emails =   explode(",", $room->notify_email);
                        foreach($emails as $email) {
                            Mail::send('emails.newbookingahoervice', ['booking' => $booking, 'user'  =>  Auth::user(),  'room'  =>  $room], function ($m) use ($room) {
                                $m->from('newintra@kodeks.ru', 'Новый корпоративный портал');
                                $m->to($email)->subject('Новое бронирование в переговорной '    .   $room->name);
                            });
                        }
                    }
                    return response()->json(['result' => 'success']);
                }
            }
        }
    }

    public function viewbooking($id) {
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
        
        $ukot_presence   =   $request->input('ukot_presence_change');
        if(is_null($ukot_presence)) {
            $ukot_presence   =   0;
        }
        
        $notes          = trim($request->input('notes_change'));
        
        $aho_presence   =   $request->input('aho_presence');
        if(is_null($aho_presence)) {
            $aho_presence   =   0;
        }
        
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
            if($ukot_presence) {
                if(!mb_strlen($notes,    "UTF-8")) {
                    return response()->json(['error',  'message' =>  'notes required for ukot',    'field' =>  'notes_change']);
                }
            }
            if($aho_presence) {
                $caldate    =   new Date();
                $service_date   =   $caldate->add(new DateInterval("P1D"));
                if($date_booking < $service_date) {
                    return response()->json(['error',  'message' =>  'correct interval for aho',    'field' =>  'input_date_booking']);
                }
            }
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
                    $booking->service_ukot          =   $ukot_presence;
                    $booking->service_aho           =   $aho_presence;
                    $booking->notes =   $notes;
                    $booking->save();

                    if(!$booking->approved  &&  $room->notify_email) {
                        $emails =   explode(",", $room->notify_email);
                        foreach($emails as $email) {
                            Mail::send('emails.newbooking', ['booking' => $booking, 'user'  =>  Auth::user(),  'room'  =>  $room], function ($m) use ($room) {
                                $m->from('newintra@kodeks.ru', 'Новый корпоративный портал');
                                $m->to($email)->subject('Новое бронирование в переговорной '    .   $room->name);
                            });
                        }
                    }
                    if($booking->service_aho    &&  $room->notify_email) {
                        $emails =   explode(",", $room->notify_email);
                        foreach($emails as $email) {
                            Mail::send('emails.newbookingahoervice', ['booking' => $booking, 'user'  =>  Auth::user(),  'room'  =>  $room], function ($m) use ($room) {
                                $m->from('newintra@kodeks.ru', 'Новый корпоративный портал');
                                $m->to($email)->subject('Новое бронирование в переговорной '    .   $room->name);
                            });
                        }
                    }
                    return response()->json(['result' => 'success']);
                }
            }
        }
    }

    public function deletebooking($id) {
        $booking = Booking::findOrFail($id);
        if (Auth::check()) {
            if ($booking->user_id == Auth::user()->id) {
                $booking->delete();
            }
        }
        return redirect(route('rooms.book', ["id"  =>  $booking->room_id]));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
}
