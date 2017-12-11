<?php
/**
 * Created by PhpStorm.
 * User: viach
 * Date: 11.12.2017
 * Time: 10:18
 */

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DateTime;

class UserController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $users = User::orderBy('name', 'asc')->get();
        return view('users.birthday', ['users'    =>  $users]);
    }

    public function unit($id)
    {
        $user = User::leftJoin('deps_peoples', 'users.id', '=', 'deps_peoples.people_id')
            ->leftJoin('deps', 'deps_peoples.dep_id', '=', 'deps.id')
            ->findOrFail($id);

        return view('users.unit', ['user'    =>  $user]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
}