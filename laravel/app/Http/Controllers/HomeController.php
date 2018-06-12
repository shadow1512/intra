<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Adldap\Laravel\Facades\Adldap;
use App\News;
use App\User;
use App\Rooms;
use DB;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //новости
        $news = News::orderBy('importancy', 'desc')->limit(5)->get();

        //дни рождения
        $dt = date("z");
        $dt1 = $dt + 3;
        $users = User::leftJoin('deps_peoples', 'users.id', '=', 'deps_peoples.people_id')

        ->whereBetween(DB::raw("DAYOFYEAR(birthday)"), [$dt, $dt1])
                ->orderBy('birthday', 'asc')->get();

        //новые сотрудники
        $newusers = User::leftJoin('deps_peoples', 'users.id', '=', 'deps_peoples.people_id')
            ->whereRaw("ADDDATE(workstart, INTERVAL 1 MONTH) >= '" . date("Y-m-d") . "'")
            ->orderBy('workstart', 'desc')->get();

        //Комнаты
        $rooms = Rooms::orderBy('name')->get();

        //Контакты выбранные
        $contacts = array();
        if(Auth::check()) {
            $contacts = User::select("users.id", "users.avatar", "users.fname", "users.lname", "users.mname", "users.position", "users.email", "users.phone")
                ->leftJoin('user_contacts', 'user_contacts.contact_id', '=', 'users.id')->where('user_contacts.user_id', '=', Auth::user()->id)->get();
        }

        return view('home', ['news'    =>  $news, 'users'   =>  $users, 'newusers'=>$newusers, 'rooms'  =>  $rooms, 'contacts'  =>  $contacts]);
    }
}
