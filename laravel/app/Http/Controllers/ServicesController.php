<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use DB;
use Auth;
use App\Rooms;

class ServicesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function teh()
    {
        //Комнаты
        $rooms = Rooms::orderBy('name')->get();

        return view('services.teh', ["rooms"    =>  $rooms]);
    }

    public function cartridge()
    {
        return view('services.cartridge', ["rooms"    =>  $rooms]);
    }

    public function mail()
    {
        return view('services.mail', ["rooms"    =>  $rooms]);
    }
}
