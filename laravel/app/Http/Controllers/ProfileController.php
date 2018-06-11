<?php
/**
 * Created by PhpStorm.
 * User: viach
 * Date: 11.12.2017
 * Time: 10:18
 */

namespace App\Http\Controllers;

use Auth;
use App\User;
use DB;
use PDO;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DateTime;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contacts = User::select("users.id", "users.avatar", "users.fname", "users.lname", "users.mname", "users.position", "users.email", "users.phone")
            ->leftJoin('user_contacts', 'user_contacts.contact_id', '=', 'users.id')->where('user_contacts.user_id', '=', Auth::user()->id)->get();

        return view('profile.view', ['contacts'    =>  $contacts]);
    }

    public function edit()
    {

    }

    public function update(Request $request)
    {

    }

    public function addcontact($id)
    {
        DB::table('user_contacts')->insert(
            ['user_id' => Auth::user()->id, 'contact_id' => $id]
        );

        return redirect('/people/unit/' . $id);
    }


}