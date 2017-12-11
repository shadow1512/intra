<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Adldap\Laravel\Facades\Adldap;
use App\News;
use App\User;
use DB;

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
            ->where(DB::raw("ADDDATE(workstart, INTERVAL 1 MONTH)"), '>=', "'" . date("Y-m-d") . "'")
            ->orderBy('workstart', 'desc')->get();
        return view('home', ['news'    =>  $news, 'users'   =>  $users, 'newusers'=>$newusers]);
    }
}
