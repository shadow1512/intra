<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        $user   =   User::findOrFail(Auth::user()->id);

        return view('services.teh', ['user' =>  $user]);
    }

    public function cartridge()
    {
        return view('services.cartridge');
    }

    public function mail()
    {
        return view('services.mail');
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

                $dep    =   Dep::findOrFail($user->dep_id);
                $length = mb_strlen($dep->parent_id, "UTF-8");
                $parent_root  =   $dep->parent_id;
                if ($length > 2) {
                    $parent_root = mb_substr($dep->parent_id, 0, 2);
                }

                $rootdep = Dep::where('parent_id', '=', $parent_root)->firstOrFail();

                $tr =   new Technical_Request();
                $tr->type_request   =   $trequest;
                $tr->room           =   $room;
                $tr->fio            =   $user->name;
                $tr->dep            =   $rootdep->name;
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
