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

        if (Adldap::getProvider('default')->auth()->attempt($request->input('login'), $request->input('pass'))) {
            $user = Adldap::getProvider('default')->search()->users()->find($request->input('login'));
            if($user) {
                $guid = $user->getConvertedGuid();
                $user = User::select("users.id")->leftJoin('user_keys', 'user_keys.user_id', '=', 'users.id')->where('user_keys.key', '=', $guid)->limit(1)->first();
                if($user) {
                    Auth::loginUsingId($user->id, true);
                    return response()->json(['ok']);
                }
                else {
                    return response()->json(['error', 'no linked user']);
                }
            }
            else {
                return response()->json(['error', 'no ldap user']);
            }
        }
        else {
            return response()->json(['error', 'wrong credentials']);
        }

    }

    public function logout() {
        Auth::logout();
        return redirect('/');
    }
}
