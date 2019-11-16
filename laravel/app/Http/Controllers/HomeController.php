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
                    $query->where(DB::raw("MONTH(birthday)"), '=',   $m)->where(DB::raw("DAY(birthday)"),    '=',    $d);
                })
                ->orWhere(function($query) use ($d,  $m) {
                    $query->where(DB::raw("MONTH(SUBDATE(birthday, 1))"),   '=',    $m)->where(DB::raw("DAY(SUBDATE(birthday, 1))"), '=',    $d);
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

        //режим работы столовой
        $items  =   Dinner_slots::orderBy('time_start')->get();

        $summ   =   0;
        if (Auth::check()) {
            $bill = DB::table('users_dinner_bills')->where('user_id', Auth::user()->id)->orderBy('date_created', 'desc')->first();
            if($bill) {
                $summ   =   $bill->summ;
            }
        }

        return view('home', [   'news'    =>  $news, 'users'   =>  $users, 'newusers'=>$newusers,
                                'hide_dinner'   =>Cookie::get('hide_dinner'),
                                'ditems'        =>  $items,
                                'summ'          =>  $summ]);
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

    public function feedback()
    {
        //Форма обратной связи
        return view('feedback.form');
    }

    public function feedbacksuccess()
    {
        //Успех отправки формы
        return view('feedback.success');
    }

    public function storefeedback(Request $request)
    {
        $feedback   =   trim($request->input('feedback'));


        $messages   =   array(  "feedback.required"     =>  "Поле обязательно для заполнения",
                                "feedback.max"          =>  "Поле не должно превышать 16000 символов",
        );

        $validator = Validator::make($request->all(), [
            'feedback'               => 'required|max:16000',
        ],  $messages);


        if ($validator->fails()) {
            return redirect()->route('feedback')
                ->withErrors($validator)
                ->withInput();
        }
        else {
            if (Auth::check()) {

                $fb =   new Feedback();
                $fb->feedback   =   $feedback;
                $fb->user_id    =   Auth::user()->id;
                $fb->save();
                return redirect()->route('feedback.success');
            }
            else {
                return redirect()->route('feedback');
            }
        }
    }
}
