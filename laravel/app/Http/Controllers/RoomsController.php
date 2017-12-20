<?php

namespace App\Http\Controllers;

use App\Rooms;
use App\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DateTime;

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
        $bookings = array();
        return view('rooms.order', ['room'    =>  $room, 'bookings'   =>  $bookings]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
}
