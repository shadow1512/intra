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

        $validator = Validator::make($request->all(), [
            'input_name'            => 'required|max:90',
            'input_date_booking'    => 'required',
            'input_time_start'      => 'required',
            'input_time_end'        => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error', $validator]);
        }
        else {
            //Нужно проверить, что не перекрывается по датам
            $exists =   Booking::whereDate('date_book',    $date_booking)
                            ->where(function($query) use ($time_start,  $time_end) {
                                        $query->whereBetween('time_start',  [$time_start,   $time_end])->orWhereBetween('time_end', [$time_start,   $time_end]);
                                    })->exists();

            if($exists) {
                return response()->json(['result'    =>  'error',  'message' =>  'crossing detected']);
            }
            else {
                $date = date("Y-m-d H:i:s");
                DB::table('room_bookings')->insert(['room_id' => $id, 'name' => $name, 'date_book' => $date_booking,
                    'user_id' => Auth::user()->id, 'time_start' => $time_start, 'time_end' => $time_end,
                    'created_at' => $date, 'updated_at' => $date]);
                return response()->json(['result'   =>  'success']);
            }
        }
    }

    public function viewbooking($id) {
        $booking =  Booking::findOrFail($id);
        $room   =   Rooms::findOrFail($booking->room_id);
        $rooms  =   Rooms::orderBy('name')->get();
        if($booking->user_id    ==  Auth::user()->id) {
            return response()->json(['result'   =>  'success',  'html'  =>  view('rooms.change', ['room'    =>  $room, 'bookings'   =>  $booking,   'rooms' =>  $rooms])]);
        }
        else {
            return response()->json(['result'   =>  'error',    'text'  =>  'Вы не можете изменять это бронирование, т.к. не вы его создавали']);
        }
    }

    public function savebooking($id, Request $request) {

    }

    public function deletebooking($id) {
        $booking = Booking::findOrFail($id);
        if($booking->user_id    ==  Auth::user()->id) {
            $booking->delete();
        }
        return redirect(route('rooms.book', ["id"  =>  $booking->id]));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
}
