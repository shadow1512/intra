<?php

namespace App\Http\Controllers;

use App\Rooms;
use App\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DateTime;
use DateInterval;
use DB;
use Auth;
use View;

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
        $caldate->add(new DateInterval("P4D"));

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

        $messages   =   array(  "input_name.required"           =>  "Название мероприятия - обязательное поле",
                                "input_name.max"                =>  "Название мероприятие не должно быть длиннее, чем 90 символов",
                                "input_date_booking.required"   =>  "Дата бронирования - обязательное поле",
                                "input_time_start.required"     =>  "Время начала бронирования - обязательное поле",
                                "input_time_end.required"       =>  "Время окончания бронирования - обязательное поле",
                                "input_time_start.date_format"  =>  "Время начала бронирования должно быть в формате ЧЧ:ММ",
                                "input_time_end.date_format"    =>  "Время окончания бронирования должно быть в формате ЧЧ:ММ"
        );

        $validator = Validator::make($request->all(), [
            'input_name'            => 'required|max:90',
            'input_date_booking'    => 'required',
            'input_time_start'      => 'required|date_format:H:i',
            'input_time_end'        => 'required|date_format:H:i',
        ],  $messages);

        if ($validator->fails()) {
            return response()->json(['error', $validator->errors()]);
        }
        else {
            if($time_start  <   Config::get('rooms.time_start_default')) {
                return response()->json(['error',  'message' =>  'time start too early',    'field' =>  'input_time_start']);
            }
            if($time_end  <   Config::get('rooms.time_end_default')) {
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
                    DB::table('room_bookings')->insert(['room_id' => $id, 'name' => $name, 'date_book' => $date_booking,
                        'user_id' => Auth::user()->id, 'time_start' => $time_start, 'time_end' => $time_end,
                        'created_at' => $date, 'updated_at' => $date]);
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

        $messages   =   array(  "input_name_change.required"            =>  "Название мероприятия - обязательное поле",
                                "input_name_change.max"                 =>  "Название мероприятие не должно быть длиннее, чем 90 символов",
                                "input_date_booking_change.required"    =>  "Дата бронирования - обязательное поле",
                                "input_time_start_change.required"      =>  "Время начала бронирования - обязательное поле",
                                "input_time_end_change.required"        =>  "Время окончания бронирования - обязательное поле",
                                "input_time_start.date_format"  =>  "Время начала бронирования должно быть в формате ЧЧ:ММ",
                                "input_time_end.date_format"    =>  "Время окончания бронирования должно быть в формате ЧЧ:ММ",
                                "input_room.required"                   =>  "Комната - обязательное поле"
        );

        $validator = Validator::make($request->all(), [
            'input_name_change'                 =>  'required|max:90',
            'input_date_booking_change'         =>  'required',
            'input_time_start_change'           =>  'required|date_format:H:i',
            'input_time_end_change'             =>  'required|date_format:H:i',
            'input_room'                        =>  'required',
        ],  $messages);

        if ($validator->fails()) {
            return response()->json(['error', $validator->errors()]);
        }
        else {

            if($time_start  <   Config::get('rooms.time_start_default')) {
                return response()->json(['error',  'message' =>  'time start too early',    'field' =>  'input_time_start_change']);
            }
            if($time_end  <   Config::get('rooms.time_end_default')) {
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
                    $booking->room_id       =   $room;
                    $booking->name          =   $name;
                    $booking->date_book     =   $date_booking;
                    $booking->time_start    =   $time_start;
                    $booking->time_end      =   $time_end;
                    $booking->updated_at    =   $date;
                    $booking->save();

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
