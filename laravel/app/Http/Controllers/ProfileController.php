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
use Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
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
        $user = DB::table('user_contacts')->where('user_id', Auth::user()->id)->where('contact_id', $id)->first();
        if(!$user) {
            DB::table('user_contacts')->insert(
                ['user_id' => Auth::user()->id, 'contact_id' => $id]
            );
        }

        return redirect('/people/unit/' . $id);
    }

    public function deleteavatar()
    {
        $default = Config::get('image.default_avatar');
        DB::table('users')->update(
            ['avatar' => $default, 'updated_at' => date("Y-m-d H:i:s")]
        );

        print json_encode(array("ok", $default));
    }

    public function updateavatar(Request $request)
    {
        $path   =   Storage::disk('public')->putFile(Config::get('image.avatar_path'), $request->file('input_avatar'), 'public');
        $size   =   Storage::disk('public')->getSize($path);
        $type   =   Storage::disk('public')->getMimetype($path);

        if($size <= 3000000) {
            if($type == "image/jpeg" || $type == "image/pjpeg" || $type == "image/png") {
                $manager = new ImageManager(array('driver' => 'imagick'));
                $image  = $manager->make(storage_path('app/public') . '/' . $path)->fit(Config::get('image.avatar_width'))->save(storage_path('app/public') . '/' . $path);
                DB::table('users')->update(
                    ['avatar' => Storage::disk('public')->url($path), 'updated_at' => date("Y-m-d H:i:s")]
                );
                print json_encode(array("ok",   Storage::disk('public')->url($path)));
            }
            else {
                print json_encode(array("error", "file wrong type"));
            }
        }
        else {
            print json_encode(array("error", "file too large"));
        }

    }
}