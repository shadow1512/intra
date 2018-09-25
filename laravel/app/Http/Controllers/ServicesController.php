<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use DB;
use Auth;
use App\Rooms;

class ServicesController extends Controller
{
    public $rooms;

    public $contacts;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');

        //Комнаты
        $this->rooms = Rooms::orderBy('name')->get();
        //Контакты выбранные
        if(Auth::check()) {
            $this->contacts = User::select("users.id", "users.avatar", "users.fname", "users.lname", "users.mname", "users.position", "users.email", "users.phone")
                ->leftJoin('user_contacts', 'user_contacts.contact_id', '=', 'users.id')->where('user_contacts.user_id', '=', Auth::user()->id)->get();
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function teh()
    {
        return view('services.teh', ["rooms"    =>  $this->rooms, "contacts"  =>  $this->contacts]);
    }

    public function cartridge()
    {
        return view('services.cartridge', ["rooms"    =>  $this->rooms, "contacts"  =>  $this->contacts]);
    }

    public function mail()
    {
        return view('services.mail', ["rooms"    =>  $this->rooms, "contacts"  =>  $this->contacts]);
    }
}
