<?php

namespace App\Http\Controllers;

use Cookie;
use Illuminate\Cookie\CookieJar;
use Illuminate\Http\Request;
use Adldap\Laravel\Facades\Adldap;
use App\News;
use App\User;
use App\Rooms;
use App\Dinner_slots;
use DB;
use Auth;
use DateTime;
use DateInterval;

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
        $news = News::orderBy('importancy', 'desc')->limit(15)->get();

        //дни рождения
        $dt = new DateTime();

        $d  =   $dt->format("d");
        $m  =   $dt->format("m");

        $users = User::select("users.id", "users.name", "users.avatar", "users.fname", "users.lname", "users.mname", "users.position", "users.email", "users.phone", "deps_peoples.work_title", "users.birthday")
                ->leftJoin('deps_peoples', 'users.id', '=', 'deps_peoples.people_id')
                ->where(function($query) use ($d,  $m) {
                    $query->where(DB::raw("MONTH(birthday)    =   '$m'"))->where(DB::raw("DAY(birthday)    =   '$d'"));
                })
                ->orWhere(function($query) use ($d,  $m) {
                    $query->where(DB::raw("MONTH(SUBDATE(birthday, 1))"),   '=',    $m)->where(DB::raw("DAY(SUBDATE(birthday, 1))"), '=',    $d));
                })
                ->orWhere(function($query) use ($d,  $m) {
                    $query->where(DB::raw("MONTH(SUBDATE(birthday, 2))"),   '=',    $m)->where(DB::raw("DAY(SUBDATE(birthday, 2))"), '=',    $d);
                })
                ->orderByRaw('MONTH(birthday)', 'asc')->orderByRaw('DAY(birthday)', 'asc')->get();

        //новые сотрудники
        $newusers = User::select("users.id", "users.name", "users.avatar", "users.fname", "users.lname", "users.mname", "users.position", "users.email", "users.phone", "deps_peoples.work_title", "users.workstart")
            ->leftJoin('deps_peoples', 'users.id', '=', 'deps_peoples.people_id')
            ->whereRaw("ADDDATE(workstart, INTERVAL 1 MONTH) >= '" . date("Y-m-d") . "'")
            ->orderBy('workstart', 'desc')->get();

        //счет в столовой
        $summ   =   0;
        $bills  =   array();
        if (Auth::check()) {
            $bill = DB::table('users_dinner_bills')->where('user_id', Auth::user()->id)->orderBy('date_created', 'desc')->first();
            if($bill) {
                $summ   =   $bill->summ;
            }
            $bills =   DB::table('users_dinner_bills')->selectRaw('MONTH(date_created) as mdc, MAX(summ) as ms')->where("user_id", "=",   Auth::user()->id)->groupBy('mdc')->limit(8)->get();
        }

        //режим работы столовой
        $items  =   Dinner_slots::orderBy('time_start')->get();

        //Меню
        $ch = curl_init('http://intra.lan.kodeks.net/cooking/menu1.html');
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $res = curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $kitchen_menu   =   "";
        $res=   iconv("windows-1251",    "utf-8",   $res);
        if($status_code == 200) {
            preg_match("/<body[^>]*>(.*?)<\/body>/ius", $res, $matches);
            if(count($matches)) {
                $kitchen_menu   =   $matches[1];
            }
        }


        return view('home', [   'news'    =>  $news, 'users'   =>  $users, 'newusers'=>$newusers,
                                'hide_dinner'   =>Cookie::get('hide_dinner'),
                                'kitchen_menu'  =>  $kitchen_menu,
                                'summ'          =>  $summ,
                                'bills'         =>  $bills,
                                'ditems'        =>  $items]);
    }

    function parking()
    {
        $users =    User::where('numpark', '>', 0)->orderBy('numpark', 'asc')->get();
        //Комнаты
        return view('users.parking', ['users'   =>  $users]);
    }

    function staff()
    {
        //Полезные документы
        return view('staff');
    }
}
