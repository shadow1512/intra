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
        $contacts = User::select("users.id", "users.avatar", "users.fname", "users.lname", "users.mname", "users.position", "users.email", "users.phone","users.birthday")
            ->leftJoin('user_contacts', 'user_contacts.contact_id', '=', 'users.id')->where('user_contacts.user_id', '=', Auth::user()->id)->get();

        return view('profile.view', ['contacts'    =>  $contacts]);
    }

    public function edit()
    {

    }

    public function update(Request $request)
    {
        $fname          = trim($request->input('input_fname'));
        $mname          = trim($request->input('input_mname'));
        $lname          = trim($request->input('input_lname'));
        $address        = trim($request->input('input_address'));
        $room           = trim($request->input('input_room'));
        $phone          = trim($request->input('input_phone'));
        $mobile_phone   = trim($request->input('input_mobile_phone'));
        $position_desc  = trim($request->input('input_position_desc'));

        $validator = Validator::make($request->all(), [
            'input_fname'           => 'required|max:255',
            'input_mname'           => 'max:255',
            'input_lname'           => 'required|max:255',
            'input_address'         => 'max:255',
            'input_room'            => 'max:3',
            'input_phone'           => 'max:24',
            'input_mobile_phone'    => 'max:24',
            'input_position_desc'   => 'max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['error', $validator]);
        }
        else {
            DB::table('users')->where("id", "=", Auth::user()->id)
                ->update(['fname' => $fname, 'lname'    =>  $lname, 'mname' =>  $mname,
                            'room'  =>  $room,  'address'   =>  $address,   'phone' =>  $phone,
                            'mobile_phone'  =>  $mobile_phone,  'position_desc' =>  $position_desc,
                            'updated_at' => date("Y-m-d H:i:s")]);
            return response()->json(['success']);
        }

    }

    public function addcontact($id)
    {
        if (Auth::check()) {
            $user = DB::table('user_contacts')->where('user_id', Auth::user()->id)->where('contact_id', $id)->first();
            if (!$user) {
                DB::table('user_contacts')->insert(
                    ['user_id' => Auth::user()->id, 'contact_id' => $id]
                );
            }
        }

        return redirect('/people/unit/' . $id);
    }

    public function deleteavatar()
    {
        $default = Config::get('image.default_avatar');
        DB::table('users')->where("id", "=", Auth::user()->id)
            ->update(['avatar' => $default, 'updated_at' => date("Y-m-d H:i:s")]);

        return response()->json(['ok', $default]);
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
                DB::table('users')->where("id", "=", Auth::user()->id)
                    ->update(['avatar' => Storage::disk('public')->url($path), 'updated_at' => date("Y-m-d H:i:s")]);
                return response()->json(['ok', Storage::disk('public')->url($path)]);
            }
            else {
                return response()->json(['error', 'file wrong type']);
            }
        }
        else {
            return response()->json(['error', 'file too large']);
        }

    }
}