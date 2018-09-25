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

    public function book($id)
    {
        $room = Rooms::findOrFail($id);
        //
        $curweekday = date("N");
        $subperiod  = 0;
        if($curweekday  > 1) {
            $subperiod  = $curweekday - 1;
        }
        $caldate = new DateTime();
        if($subperiod) {
            $caldate = $caldate->sub(new DateInterval("P" . $subperiod . "D"));
        }
        $findate    =   $caldate->add(new DateInterval("P4D"));

        DB::listen(function($sql, $bindings, $time) {
            var_dump($sql);
            var_dump($bindings);
            var_dump($time);
        });

        $bookings = Booking::select('room_bookings.*',
                                            'users.name as person_name', 'users.phone as person_phone', 'users.email as person_email', 'users.fname as fname',  'users.lname as lname', 'users.avatar as avatar')
            ->selectRaw('TIMESTAMPDIFF(MINUTE,  room_bookings.time_start,   room_bookings.time_end) as duration')
            ->leftJoin("users", 'room_bookings.user_id', '=', 'users.id')
            ->whereBetween('date_book', [$caldate,  $findate])
            ->orderBy('date_book')
            ->orderBy('time_start')
            ->get();

        var_dump($bookings);

        $bookings = Booking::select('room_bookings.*',
            'users.name as person_name', 'users.phone as person_phone', 'users.email as person_email', 'users.fname as fname',  'users.lname as lname', 'users.avatar as avatar')
            ->selectRaw('TIMESTAMPDIFF(MINUTE,  room_bookings.time_start,   room_bookings.time_end) as duration')
            ->leftJoin("users", 'room_bookings.user_id', '=', 'users.id')
            ->whereBetween('date_book', [$caldate,  $findate])
            ->orderBy('date_book')
            ->orderBy('time_start')
            ->toSql();

        dd($bookings);
        $bookings_by_dates = array();
        foreach($bookings as $booking) {
            $bookings_by_dates[strtotime($booking->date_book)][] = $booking;
        }

        return view('rooms.order', ['room'    =>  $room, 'bookings'   =>  $bookings_by_dates]);
    }

    public function createbooking($id, Request $request) {
        $name          = trim($request->input('input_name'));
        $date_booking  = trim($request->input('input_date_booking'));
        $time_start    = trim($request->input('input_time_start'));
        $time_end      = trim($request->input('input_time_end'));

        $validator = Validator::make($request->all(), [
            'input_name'            => 'required|max:255',
            'input_date_booking'    => 'required',
            'input_time_start'      => 'required',
            'input_time_end'        => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error', $validator]);
        }
        else {
            $date = date("Y-m-d H:i:s");
            DB::table('room_bookings')->insert(['room_id' => $id, 'name'    =>  $name, 'date_book' =>  $date_booking,
                    'user_id'  =>  Auth::user()->id,  'time_start'   =>  $time_start,   'time_end' =>  $time_end,
                    'created_at'    =>  $date, 'updated_at' => $date]);
            return response()->json(['success']);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
}
