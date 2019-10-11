<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Dep;
use DB;
use Auth;
use App\Technical_Request;

class ServicesController extends Controller
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
    public function teh()
    {
        $user=  null;

        if (Auth::check()) {
            $user = User::findOrFail(Auth::user()->id);
        }

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

        //камеры
        $cam1   =   $cam2   =   null;

        $ch = curl_init('http://intra-unix.kodeks.net/img/cam1.jpg');
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FILETIME, true);
        $res = curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $time   =   curl_getinfo($ch,   CURLINFO_FILETIME);
        if($status_code == 200) {
            if($time    >   -1) {
                if((time()   -   $time) <=   600) {
                    $cam1   =   "ok";
                }
            }
        }

        $ch = curl_init('http://intra-unix.kodeks.net/img/cam2.jpg');
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FILETIME, true);
        $res = curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $time   =   curl_getinfo($ch,   CURLINFO_FILETIME);
        if($status_code == 200) {
            if($time    >   -1) {
                if((time()   -   $time) <=   600) {
                    $cam2   =   "ok";
                }
            }
        }

        return view('services.teh', [   'user' =>  $user,
                                        'kitchen_menu'  =>  $kitchen_menu,
                                        'summ'          =>  $summ,
                                        'bills'         =>  $bills,
                                        'cam1'          =>  $cam1,
                                        'cam2'          =>  $cam2]);
    }

    public function cartridge()
    {
        $user=  null;

        if (Auth::check()) {
            $user = User::findOrFail(Auth::user()->id);
        }

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

        //камеры
        $cam1   =   $cam2   =   null;

        $ch = curl_init('http://intra-unix.kodeks.net/img/cam1.jpg');
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FILETIME, true);
        $res = curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $time   =   curl_getinfo($ch,   CURLINFO_FILETIME);
        if($status_code == 200) {
            if($time    >   -1) {
                if((time()   -   $time) <=   600) {
                    $cam1   =   "ok";
                }
            }
        }

        $ch = curl_init('http://intra-unix.kodeks.net/img/cam2.jpg');
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FILETIME, true);
        $res = curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $time   =   curl_getinfo($ch,   CURLINFO_FILETIME);
        if($status_code == 200) {
            if($time    >   -1) {
                if((time()   -   $time) <=   600) {
                    $cam2   =   "ok";
                }
            }
        }

        return view('services.cartridge', [ 'user' =>  $user,
                                            'kitchen_menu'  =>  $kitchen_menu,
                                            'summ'          =>  $summ,
                                            'bills'         =>  $bills,
                                            'cam1'          =>  $cam1,
                                            'cam2'          =>  $cam2]);
    }

    public function mail()
    {
        $user=  null;

        if (Auth::check()) {
            $user = User::findOrFail(Auth::user()->id);
        }
        
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

        //камеры
        $cam1   =   $cam2   =   null;

        $ch = curl_init('http://intra-unix.kodeks.net/img/cam1.jpg');
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FILETIME, true);
        $res = curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $time   =   curl_getinfo($ch,   CURLINFO_FILETIME);
        if($status_code == 200) {
            if($time    >   -1) {
                if((time()   -   $time) <=   600) {
                    $cam1   =   "ok";
                }
            }
        }

        $ch = curl_init('http://intra-unix.kodeks.net/img/cam2.jpg');
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FILETIME, true);
        $res = curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $time   =   curl_getinfo($ch,   CURLINFO_FILETIME);
        if($status_code == 200) {
            if($time    >   -1) {
                if((time()   -   $time) <=   600) {
                    $cam2   =   "ok";
                }
            }
        }
        return view('services.mail', [ 'user' =>  $user,
            'kitchen_menu'  =>  $kitchen_menu,
            'summ'          =>  $summ,
            'bills'         =>  $bills,
            'cam1'          =>  $cam1,
            'cam2'          =>  $cam2]);
    }

    public function storeRequest(Request $request) {
        $room           =   trim($request->input('roomnum'));
        $printer        =   trim($request->input('printer'));
        $user_comment   =   trim($request->input('user_comment'));
        $phone          =   trim($request->input('phone'));
        $email          =   trim($request->input('email'));
        $trequest       =   trim($request->input('type_request'));

        $messages   =   array(  "roomnum.required"          =>  "Поле обязательно для заполнения",
                                "roomnum.max"               =>  "Поле не должно превышать 10 символов",
                                "phone.required"            =>  "Поле обязательно для заполнения",
                                "phone.max"                 =>  "Поле не должно превышать 10 символов",
                                "email.required"            =>  "Поле обязательно для заполнения",
                                "email.email"               =>  "Неверный формат поля",
                                "email.max"                 =>  "Поле не должно превышать 255 символов",
                                "user_comment.required"     =>  "Поле обязательно для заполнения",
                                "user_comment.max"          =>  "Поле не должно превышать 255 символов",

        );

        if($trequest  ==  "cartridge") {
            $validator = Validator::make($request->all(), [
                'roomnum'               => 'required|max:10',
            ],  $messages);
        }
        if($trequest  ==  "teh") {
            $validator = Validator::make($request->all(), [
                'roomnum'               =>  'required|max:10',
                'phone'                 =>  'required|max:18',
                'email'                 =>  'required|email|max:255',
                'user_comment'          =>  'required|max:255',
            ],  $messages);
        }


        if ($validator->fails()) {
            return response()->json(['error', $validator->errors()]);
        }
        else {
            if (Auth::check()) {

                $user   =   User::select("users.*", "deps_peoples.work_title",  "deps_peoples.dep_id")
                    ->leftJoin('deps_peoples', 'users.id', '=', 'deps_peoples.people_id')
                    ->where('users.id', '=', Auth::user()->id)->first();

                $dpname =   null;
                if($user->dep_id) {
                    $dep    =   Dep::findOrFail($user->dep_id);
                    $length = mb_strlen($dep->parent_id, "UTF-8");
                    $parent_root  =   $dep->parent_id;
                    if ($length > 2) {
                        $parent_root = mb_substr($dep->parent_id, 0, 2);
                    }

                    $rootdep = Dep::where('parent_id', '=', $parent_root)->firstOrFail();
                    $dpname =   $rootdep->name;
                }


                $tr =   new Technical_Request();
                $tr->type_request   =   $trequest;
                $tr->room           =   $room;
                $tr->user_id        =   Auth::user()->id;
                $tr->fio            =   $user->name;
                $tr->dep            =   $dpname;
                if($trequest  ==  "cartridge") {
                    $tr->printer    =   $printer;
                }
                if($trequest  ==  "teh") {
                    $tr->phone    =   $phone;
                    $tr->email    =   $email;
                }
                $tr->user_comment    =   $user_comment;

                $tr->save();
                return response()->json(['result' => 'success']);
            }
            else {
                return response()->json(['error', 'auth error']);
            }
        }
    }
}
