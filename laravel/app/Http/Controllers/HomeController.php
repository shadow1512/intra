<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Adldap\Laravel\Facades\Adldap;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $users = Adldap::search()->users()->get();
        var_dump($users);
        exit();
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }
}
