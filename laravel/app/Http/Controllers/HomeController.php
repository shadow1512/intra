<?php

namespace App\Http\Controllers;

use Cookie;
use Illuminate\Cookie\CookieJar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Adldap\Laravel\Facades\Adldap;
use Illuminate\Support\Facades\Validator;
use App\News;
use App\User;
use App\Rooms;
use App\Feedback;
use App\Dinner_slots;
use App\Dinner_booking;
use App\Static_Pages;
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
    public function index(Request $request)
    {
        //
        if($request->input('nocache')) {
            Cache::flush();
        }
        //новости
        $news = News::orderBy('importancy', 'desc')->orderBy('published_at',    'DESC')->limit(15)->get();

        //дни рождения
        $dt = new DateTime();

        $d  =   $dt->format("d");
        $m  =   $dt->format("m");

        $users = User::select("users.id", "users.name", "users.avatar", "users.avatar_round", "users.fname", "users.lname", "users.mname", "users.position", "users.email", "users.phone", "users.ip_phone", "deps_peoples.work_title", "users.birthday")
                ->leftJoin('deps_peoples', 'users.id', '=', 'deps_peoples.people_id')
                ->whereNull('deps_peoples.deleted_at')
                ->where(function($query) use ($d,  $m) {
                    $query
                        ->where(DB::raw("MONTH(birthday)"), '=', $m)->where(DB::raw("DAY(birthday)"), '=', $d)
                        ->orWhere(function ($query) use ($d, $m) {
                            $query->where(DB::raw("MONTH(SUBDATE(birthday, 1))"), '=', $m)->where(DB::raw("DAY(SUBDATE(birthday, 1))"), '=', $d);
                        })
                        ->orWhere(function ($query) use ($d, $m) {
                            $query->where(DB::raw("MONTH(SUBDATE(birthday, 2))"), '=', $m)->where(DB::raw("DAY(SUBDATE(birthday, 2))"), '=', $d);
                        });
                })
                ->orderByRaw('MONTH(birthday)', 'asc')->orderByRaw('DAY(birthday)', 'asc')->get();

        //новые сотрудники
        $newusers = User::select("users.id", "users.name", "users.avatar", "users.avatar_round", "users.fname", "users.lname", "users.mname", "users.position", "users.email", "users.phone", "users.ip_phone", "deps_peoples.work_title", "users.workstart")
            ->leftJoin('deps_peoples', 'users.id', '=', 'deps_peoples.people_id')
            ->whereNull('deps_peoples.deleted_at')
            ->whereRaw("ADDDATE(workstart, INTERVAL 1 MONTH) >= '" . date("Y-m-d") . "'")
            ->orderBy('workstart', 'desc')->get();

        //режим работы столовой
        //19.07.2026 - добавлена возможность указывать разные режимы для разных дней недели. Для этого добавил поле "день недели", которое может быть от 1 до 7 (выбирается в админке)
        //Можно не выбирать никакой день, тогда это поле будет null, т.е. подходить для всех дней недели
        //Это костыль, можно было бы сделать поле обязательным и тогда для каждого дня нужно было бы заполнять его, но не хотелось морочиться с миграцией
        $items  =   Dinner_slots::where("day_of_week",  "=",    date("N"))->orderBy('time_start')->get();
        if(!count($items)) {
            $items  =   Dinner_slots::whereNull("day_of_week")->get();
        }

        return view('home', [   'news'    =>  $news, 'users'   =>  $users, 'newusers'=>$newusers,
                                'hide_dinner'       =>Cookie::get('hide_dinner'),
                                'ditems'            =>  $items]);
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
    
    public function greenoffice() {
        $page   =   Static_Pages::findOrFail(1);
        return view('static.greenoffice', ["page"   =>  $page]);
    }

    public function stylecorporate() {
        return view('static.stylecorporate');
    }
}
