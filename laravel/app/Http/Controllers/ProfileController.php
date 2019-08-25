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
use App\Dep;
use App\Profiles_Saved;
use App\Profiles_Saved_Data;
use App\Technical_Request;
use DB;
use PDO;
use Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use DateTime;
use View;

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
        $contacts = User::select("users.*", "deps_peoples.work_title")
            ->leftJoin('deps_peoples', 'users.id', '=', 'deps_peoples.people_id')
            ->leftJoin('user_contacts', 'user_contacts.contact_id', '=', 'users.id')->where('user_contacts.user_id', '=', Auth::user()->id)->get();

        $user   =   User::select("users.*", "deps_peoples.work_title",  "deps_peoples.dep_id")
            ->leftJoin('deps_peoples', 'users.id', '=', 'deps_peoples.people_id')
            ->where('users.id', '=', Auth::user()->id)->first();

        $dep    =   $ps     =   $moderate   =   $psd    =   null;

        $ps_record=    Profiles_Saved::where("user_id",    "=",    Auth::user()->id)->orderBy("updated_at",    "desc")->first();
        if($ps_record) {
            $ps=    $ps_record;
            $psd    =   Profiles_Saved_Data::where("ps_id", '=',    $ps->id)->get();

            foreach($psd as $item) {
                if($item->field_name    ==  "dep_id") {
                    if($item->new_value) {
                        $dep        =   Dep::findOrFail($item->new_value);
                        $moderate   =   Dep::getModerate($item->new_value);
                    }
                    else {
                        if($item->old_value) {
                            $dep        =   Dep::findOrFail($item->old_value);
                            $moderate   =   Dep::getModerate($item->old_value);
                        }
                    }
                }
            }
        }
        else {
            $dep        =   Dep::findOrFail($user->dep_id);
            $moderate   =   Dep::getModerate($user->dep_id);
        }



        $deps       =   Dep::whereNotNull("parent_id")->orderBy("parent_id")->orderByRaw("LENGTH(parent_id)")->get();

        //счет в столовой
        $summ   =   0;
        $bill = DB::table('users_dinner_bills')->where('user_id', Auth::user()->id)->orderBy('date_created', 'desc')->first();
        if($bill) {
            $summ   =   $bill->summ;
        }
        $bills =   DB::table('users_dinner_bills')->selectRaw('MONTH(date_created) as mdc, MAX(summ) as ms')->where("user_id", "=",   Auth::user()->id)->groupBy('mdc')->limit(8)->get();

        $tr     =   Technical_Request::select("technical_requests.*", "users.fname", "users.mname", "users.lname", "users.phone as sotr_phone")
                                ->leftJoin('users',    'users.id', '=',    'technical_requests.assigned')
                                ->where('user_id', '=',    Auth::user()->id)->orderByDesc("created_at")->limit(5)->get();


        return view('profile.view', ['contacts'    =>  $contacts,   'user'  =>  $user,  'dep'   =>  $dep,   'deps'  =>  $deps,  'ps'    =>  $ps,    'psd'    =>  $psd,    'summ'  =>  $summ,  'bills' =>  $bills, 'requests'  =>  $tr,    'moderate'  =>  $moderate]);
    }

    public function edit()
    {

    }

    public function update(Request $request)
    {
        $updates_fields =   array(  'fname'                     =>  trim($request->input('input_fname')),
                                    'lname'                     =>  trim($request->input('input_lname')),
                                    'mname'                     =>  trim($request->input('input_mname')),
                                    'address'                   =>  trim($request->input('input_address')),
                                    'room'                      =>  trim($request->input('input_room')),
                                    'phone'                     =>  trim($request->input('input_phone')),
                                    'birthday'                  =>  trim($request->input('input_birthday')),
                                    'mobile_phone'              =>  trim($request->input('input_mobile_phone')),
                                    'city_phone'                =>  trim($request->input('input_city_phone')),
                                    'email'                     =>  trim($request->input('input_email')),
                                    'email_secondary'           =>  trim($request->input('input_email_secondary')),
                                    'work_title'                =>  trim($request->input('input_work_title')),
                                    'dep_id'                    =>  trim($request->input('input_dep')),
                                    'position_desc'             =>  trim($request->input('input_position_desc'))
                                    );

        $messages   =   array(  "input_lname.string"    =>  "Фамилия должна быть строкой символов",
                                "input_lname.max"       =>  "Фамилия не может быть длиннее, чем 255 символов",
                                "input_lname.required"  =>  "Фамилия - обязательное поле",
                                "input_fname.string"    =>  "Имя должно быть строкой символов",
                                "input_fname.max"       =>  "Имя не может быть длиннее, чем 255 символов",
                                "input_fname.required"  =>  "Имя - обязательное поле",
                                "input_position_desc.string"    =>  "Описание деятельности должно быть строкой символов",
                                "input_position_desc.max"       =>  "Описание деятельности должно быть не более 255 символов",
                                "input_mname.string"    =>  "Отчество должно быть строкой символов",
                                "input_mname.max"       =>  "Отчество не может быть длиннее, чем 255 символов",
                                "input_phone.string"    =>  "Местный телефон должен быть строкой символов",
                                "input_phone.max"       =>  "Местный телефон не может быть длиннее, чем 3 символа",
                                "input_city_phone.string"    =>  "Городской телефон должен быть строкой символов",
                                "input_city_phone.max"       =>  "Городской телефон не может быть длиннее, чем 15 символов",
                                "input_mobile_phone.string"    =>  "Мобильный телефон должен быть строкой символов",
                                "input_mobile_phone.max"       =>  "Мобильный телефон не может быть длиннее, чем 18 символов",
                                "input_room.string"    =>  "Номер комнаты должен быть строкой символов",
                                "input_room.max"       =>  "Номер комнаты не может быть длиннее, чем 3 символа",
                                "input_email.string"    =>  "Email должен быть строкой символов",
                                "input_email.email"       =>  "Некорректный формат email",
                                "input_email_secondary.string"    =>  "Дополнительный Email должен быть строкой символов",
                                "input_email_secondary.email"     =>  "Некорректный формат дополнительного email",
                                "input_work_title.string"    =>  "Должность должна быть строкой символов",
                                "input_work_title.max"       =>  "Должность должна быть не более 255 символов"
                                );
        $validator = Validator::make($request->all(), [
            'input_position_desc'     =>  'nullable|string|max:255',
            'input_lname'             =>  'string|max:255|required',
            'input_fname'             =>  'string|max:255|required',
            'input_mname'             =>  'nullable|string|max:255',
            'input_phone'             =>  'nullable|string|max:3',
            'input_room'              =>  'nullable|string|max:3',
            'input_city_phone'        =>  'nullable|string|max:15',
            'input_mobile_phone'      =>  'nullable|string|max:18',
            'input_email'             =>  'nullable|string|email',
            'input_email_secondary'   =>  'nullable|string|email',
            'input_work_title'        =>  'nullable|string|max:255',
        ],  $messages);

        if ($validator->fails()) {
            return response()->json(['error', $validator->errors()]);
        }
        else {

            $ps =   Profiles_Saved::where("user_id",    "=",    Auth::user()->id)->first();
            if($ps) {
                Profiles_Saved_Data::where("ps_id", '=',    $ps->id)->delete();
                $ps->delete();
            }
            $ps =   new Profiles_Saved();

            $ps->user_id    =   Auth::user()->id;
            $ps->creator_id    =   Auth::user()->id;
            $ps->save();


            $dep_new    =   $dep_old    =   $moderator  =   null;

            foreach($updates_fields as $key =>  $value) {
                if($key ==  "birthday") {
                    $birthday_parts =   explode(".",    $value);
                    if(count($birthday_parts)   ==  3) {
                        $value   =   $birthday_parts[2]  .   '-' .   $birthday_parts[1]  .   '-' .   $birthday_parts[0];
                    }
                }
                if($key ==  "dep_id") {
                    if($value) {
                        $dep_new    =   Dep::findOrFail($value);
                        $moderator  =   Dep::getModerate($value);
                    }
                    else {

                    }
                }
                if($value   != Auth::user()->$key) {
                    $psd =   new Profiles_Saved_Data();

                    $psd->ps_id         =   $ps->id;
                    $psd->creator_id    =   Auth::user()->id;


                    $psd->field_name    =   $key;
                    $psd->old_value     =   Auth::user()->$key;
                    $psd->new_value     =   $value;

                    $psd->save();
                }
            }


            $user   =   User::select("users.*", "deps_peoples.work_title",  "deps_peoples.dep_id")
                ->leftJoin('deps_peoples', 'users.id', '=', 'deps_peoples.people_id')
                ->where('users.id', '=', Auth::user()->id)->first();


            if($user->dep_id) {
                $dep_old    =   Dep::findOrFail($user->dep_id);
            }

            if(is_null($moderator)  &&  $user->dep_id) {
                $moderator  =   Dep::getModerate($user->dep_id);
            }

            $labels =   array(
                "fname"             =>  "Имя",
                "mname"             =>  "Отчество",
                "lname"             =>  "Фамилия",
                "phone"             =>  "Местный телефон",
                "email"             =>  "Рабочий email",
                "city_phone"        =>  "Городской телефон",
                "mobile_phone"      =>  "Мобильный телефон",
                "room"              =>  "Комната",
                "email_secondary"   =>  "Добавочный email",
                "birthday"          =>  "Дата рождения",
                "dep_id"            =>  "Подразделение",
                "work_title"        =>  "Должность",
                "address"           =>  "Адрес",
                "position_desc"     =>  "Сфера деятельности"
            );

            $psd    =   Profiles_Saved_Data::where("ps_id", '=',    $ps->id)->get();

            $html   =   View::make('profile.viewchanges', ['labels' =>  $labels,    'psd' =>  $psd,   'dep_new'   =>  $dep_new,   'dep_old'   =>  $dep_old,   'moderator'  =>  $moderator]);

            return response()->json(['success', $html->render()]);
        }

    }

    public function addcontact($id, Request $request)
    {
        $url    =   $request->input('url');
        if(empty($url)) {
            $url    =   '/people/unit/' . $id;
        }
        if (Auth::check()) {
            $user = DB::table('user_contacts')->where('user_id', Auth::user()->id)->where('contact_id', $id)->first();
            if (!$user) {
                DB::table('user_contacts')->insert(
                    ['user_id' => Auth::user()->id, 'contact_id' => $id]
                );
            }
        }

        return redirect($url);
    }

    public function deletecontact($id, Request $request)
    {
        $url    =   $request->input('url');
        if(empty($url)) {
            $url    =   '/people/unit/' . $id;
        }
        if (Auth::check()) {
            DB::table('user_contacts')->where('user_id', Auth::user()->id)->where('contact_id', $id)->delete();
        }

        return redirect($url);
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