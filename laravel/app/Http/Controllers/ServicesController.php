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
