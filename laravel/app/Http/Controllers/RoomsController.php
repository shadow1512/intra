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
        $curdate = new DateTime();
        $caldate = $curdate->add(new DateInterval("P30D"));
        $bookings = Booking::select('room_bookings.*', 'users.name as person_name', 'users.phone as person_phone', 'users.email as person_email')
            ->leftJoin("users", 'room_bookings.user_id', '=', 'users.id')
            ->whereBetween('date_book', [date("Y-m-d"), $caldate])
            ->orderBy('date_book')
            ->orderBy('time_start')
            ->get();

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
