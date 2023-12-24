<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Adldap\Laravel\Facades\Adldap;
use App\User;
use DB;
use Auth;

class AdLoginController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $login  =   mb_strtolower(trim($request->input('login')),   "UTF-8");
        $authlogin  =   $login;
        if(!(mb_substr($login,    0,  5)  ==  'work\\')) {
            $authlogin  =   'work\\'    .   $login;
        }
        else {
            $login= mb_substr($login,    5);
        }

        /*$user = Adldap::getProvider('default')->search()->where('samaccountname',   '=',    'slava_u_s')->first();
        var_dump($user);
        exit();*/
        if (Adldap::getProvider('default')->auth()->attempt($authlogin, $request->input('pass'))) {
            $user = Adldap::getProvider('default')->search()->where('samaccountname',   '=',    $login)->first();
            if($user) {
                $sid = $user->getConvertedSid();
                $user = User::where("sid",  "=",    $sid)->first();
                if($user) {
                    Auth::loginUsingId($user->id, true);
                    return response()->json(['ok', $sid]);
                }
                else {
                    return response()->json(['error', 'no linked user', $sid]);
                }
            }
            else {
                return response()->json(['error', 'no ldap user']);
            }
        }
        else {
            $user = Adldap::getProvider('default')->search()->where('samaccountname',   '=',    'slava_u_s')->first();
            var_dump($user);
            return response()->json(['error', 'wrong credentials']);
        }

    }

    public function logout() {
        Auth::logout();
        return redirect('/');
    }
}
