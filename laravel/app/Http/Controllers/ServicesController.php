<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Dep;
use DB;
use Auth;
use App\Technical_Request;
use Config;
use View;
use Mail;

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

        return view('services.teh', [   'user' =>  $user]);
    }

    public function cartridge()
    {
        $user=  null;

        if (Auth::check()) {
            $user = User::findOrFail(Auth::user()->id);
        }

        return view('services.cartridge', [ 'user' =>  $user]);
    }

    public function mail()
    {
        $user=  null;

        if (Auth::check()) {
            $user = User::findOrFail(Auth::user()->id);
        }


        return view('services.mail', [ 'user' =>  $user]);
    }

    public function conference()
    {
        $user=  null;

        if (Auth::check()) {
            $user = User::findOrFail(Auth::user()->id);
        }


        return view('services.conference', [ 'user' =>  $user]);
    }

    public function sendConferenceRequest(Request $request)
    {
        $event_name         =   trim($request->input('event_name'));
        $provider           =   trim($request->input('provider'));
        $moderate           =   trim($request->input('moderate'));
        $typeevent          =   trim($request->input('typeevent'));
        $presentation       =   trim($request->input('presentation'));
        $responsible        =   trim($request->input('responsible'));
        $desired_date       =   trim($request->input('desired_date'));
        $desired_time       =   trim($request->input('desired_time'));
        $desired_length     =   trim($request->input('desired_length'));
        $audience           =   $request->input('audience');
        $facility           =   trim($request->input('facility'));

        $messages   =   array(  "event_name.required"           =>  "Поле обязательно для заполнения",
                                "event_name.max"                =>  "Поле не должно превышать 255 символов",
                                "responsible.required"          =>  "Поле обязательно для заполнения",
                                "responsible.max"               =>  "Поле не должно превышать 255 символов",
                                "desired_date.required"         =>  "Поле обязательно для заполнения",
                                "desired_date.date"             =>  "Дата должна быть в формате dd.mm.YYYY",
                                "desired_date.after"            =>  "Дата не должна быть раньше сегодняшней",
                                "facility.required"             =>  "Поле обязательно для заполнения",
                                "facility.max"                  =>  "Поле не должно превышать 4000 символов",
                                "audience.required"             =>  "Должно быть выбрано хотя бы одно значение",
                                "audience.between"              =>  "Должно быть выбрано хотя бы одно значение",
                                "desired_time.regex"            =>  "Время начала должно быть указано в формате чч:мм",

        );

        //regex:pattern
        $validator = Validator::make($request->all(), [
            'event_name'            =>  'required|max:255',
            'responsible'           =>  'required|max:255',
            'desired_date'          =>  'required|date|after:today',
            'facility'              =>  'required|max:4000',
            'audience'              =>  'required|array|between:1,4',
            'desired_time'          =>  array('regex:/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/'),
        ],  $messages);


        if ($validator->fails()) {
            return response()->json(["result"   =>  "error", "errors"   =>  $validator->errors()]);
        }

        $html   =   View::make('services.conference', [ 'success_sent' =>  true]);
        return response()->json(["result"   =>  "success", "content"    =>  $html->render()]);
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
                'user_comment'          =>  'required|max:4096',
            ],  $messages);
        }
        if($trequest  ==  "teh") {
            $validator = Validator::make($request->all(), [
                'roomnum'               =>  'required|max:10',
                'phone'                 =>  'required|max:18',
                'email'                 =>  'required|email|max:255',
                'user_comment'          =>  'required|max:4096',
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
                $tr->fio            =   $user->lname    .   " " .   $user->fname    .   " " .   $user->mname;
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
