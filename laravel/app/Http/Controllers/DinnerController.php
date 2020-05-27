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
            $bookings_by_times[\Carbon\Carbon::parse($booking->time_start)->format("H:i")] = $booking['num_records'];
        }

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
            $booking->time_end          =   $caldate->format("H:i");
            $booking->save();

            return response()->json(['result' => 'success']);
        }
    }

    public function listbookings() {
        $caldate = Carbon::now();

        $bookings = Dinner_booking::leftJoin('users',   'dinner_bookings.user_id',  '=',    'users.id')
            ->whereDate('dinner_booking.date_created', '=',    $caldate->toDateString())
            ->orderBy('time_start')
            ->get();

        $bookings_by_times = array();
        foreach($bookings as $booking) {
            $bookings_by_times[\Carbon\Carbon::parse($booking->time_start)->format("H:i")][] = $booking;
        }

        return view('kitchen.list', [   'periods'           =>  Config::get('dinner.dinner_slots'),
                                        'bookings'          =>  $bookings_by_times]);
    }
}
