<?php

namespace App\Http\Controllers;

use Cookie;
use Illuminate\Cookie\CookieJar;
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
        $dt1 = $dt + 5;
        $users = User::select("users.id", "users.name", "users.avatar", "users.fname", "users.lname", "users.mname", "users.position", "users.email", "users.phone", "deps_peoples.work_title")
                ->leftJoin('deps_peoples', 'users.id', '=', 'deps_peoples.people_id')
                ->whereBetween(DB::raw("DAYOFYEAR(birthday)"), [$dt, $dt1])
                ->orderBy('birthday', 'asc')->get();

        //новые сотрудники
        $newusers = User::select("users.id", "users.name", "users.avatar", "users.fname", "users.lname", "users.mname", "users.position", "users.email", "users.phone", "deps_peoples.work_title")
            ->leftJoin('deps_peoples', 'users.id', '=', 'deps_peoples.people_id')
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

        //Меню
        $ch = curl_init('http://intra.lan.kodeks.net/cooking/menu1.html');
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $res = curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $kitchen_menu   =   "";
        $res=   ("windows-1251",    "utf-8",   $res);
        if($status_code == 200) {
            preg_match("/<body[^>]*>(.*?)<\/body>/ius", $res, $matches);
            if(count($matches)) {
                $kitchen_menu   =   $matches[1];
            }
        }

        $hide_menues    =   array(Cookie::get('hide_menu_0'),   Cookie::get('hide_menu_1'), Cookie::get('hide_menu_2'), Cookie::get('hide_menu_3'), Cookie::get('hide_menu_4'), Cookie::get('hide_menu_5'), Cookie::get('hide_menu_6'));
        return view('home', [   'news'    =>  $news, 'users'   =>  $users, 'newusers'=>$newusers,
                                'rooms'  =>  $rooms, 'contacts'  =>  $contacts,  'hide_dinner'   =>Cookie::get('hide_dinner'),   'hide_menues'   =>  $hide_menues,
                                'kitchen_menu'  =>  $kitchen_menu]);
    }

    function parking()
    {
        $users =    User::where('numpark', '>', 0)->orderBy('numpark', 'asc')->get();
        //Комнаты
        $rooms = Rooms::orderBy('name')->get();
        return view('users.parking', ['users'   =>  $users, 'rooms' =>  $rooms]);
    }
}
