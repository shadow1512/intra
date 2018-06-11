<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Adldap\Laravel\Facades\Adldap;
use App\User;
use DB;

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
            echo 'YES';
            var_dump(Adldap::getProvider('default')->search()->users()->find($request->input('login')));
        } else {
            echo 'NO';
        }

    }
}
