<?php
/**
 * Created by PhpStorm.
 * User: viach
 * Date: 11.12.2017
 * Time: 10:18
 */

namespace App\Http\Controllers;

use App\Deps_Peoples;
use Auth;
use App\User;
use App\Dep;
use App\Profiles_Saved;
use App\Profiles_Saved_Data;
use App\Technical_Request;
use App\Users_Timetable;
use DB;
use PDO;
use Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use DateTime;
use DateInterval;
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

        $ps_record=    Profiles_Saved::where("user_id",    "=",    Auth::user()->id)->orderBy("id",    "desc")->first();
        if($ps_record) {
            $ps=    $ps_record;
            $psd    =   Profiles_Saved_Data::where("ps_id", '=',    $ps->id)->get();
        }

        if(!is_null($user->dep_id)) {
            $dep        =   Dep::findOrFail($user->dep_id);
        }


        //счет в столовой
        $summ   =   0;
        $bill = DB::table('users_dinner_bills')->where('user_id', Auth::user()->id)->orderBy('date_created', 'desc')->first();
        if($bill) {
            $summ   =   $bill->summ;
        }
        //$bills =   DB::table('users_dinner_bills')->selectRaw('MONTH(date_created) as mdc, MAX(summ) as ms')->where("user_id", "=",   Auth::user()->id)->groupBy('mdc')->limit(8)->get();

        $tr     =   Technical_Request::select("technical_requests.*", "users.fname", "users.mname", "users.lname", "users.phone as sotr_phone")
                                ->leftJoin('users',    'users.id', '=',    'technical_requests.assigned')
                                ->where('user_id', '=',    Auth::user()->id)->orderByDesc("created_at")->limit(5)->get();


        $change_records =   array();
        $moderators     =   array();
        $changes    =   Profiles_Saved::onlyTrashed()->where('user_id', '=',    Auth::user()->id)->where('user_informed',   '=',    0)->get();
        foreach($changes as $item) {
            $change_records[$item->id]  =   Profiles_Saved_Data::withTrashed()->where('ps_id',  '=',    $item->id)->get();
            $moderators[$item->id]      =   User::findOrFail($item->commiter_id);
            $item->user_informed    =   1;
            $item->save();
        }
        return view('profile.view', [   'contacts'    =>  $contacts,   'user'  =>  $user,  'dep'   =>  $dep,
                                        'ps'    =>  $ps, 'psd'    =>  $psd,    'summ'  =>  $summ,
                                        'requests'  =>  $tr,    'moderators'  =>  $moderators,
                                        'changes'   =>  $changes,   'change_records'    =>  $change_records,    'labels'    =>  Config::get("dict.labels")]);
    }

    public function edit()
    {
        $user   =   User::select("users.*", "deps_peoples.work_title",  "deps_peoples.dep_id")
            ->leftJoin('deps_peoples', 'users.id', '=', 'deps_peoples.people_id')
            ->where('users.id', '=', Auth::user()->id)->first();

        $dep    =   $ps     =   $moderate   =   $psd    =   null;

        $ps_record=    Profiles_Saved::where("user_id",    "=",    Auth::user()->id)->orderBy("updated_at",    "desc")->first();
        if($ps_record) {
            $ps=    $ps_record;
            $psd    =   Profiles_Saved_Data::where("ps_id", '=',    $ps->id)->get();
        }

        if(!is_null($user->dep_id)) {
            $moderate   =   Dep::getModerate($user->dep_id);
            $dep        =   Dep::findOrFail($user->dep_id);
        }

        $html   =   View::make('profile.editpopup', ['user'  =>  $user,  'dep'   =>  $dep,  'ps'    =>  $ps,
            'psd'    =>  $psd, 'moderate'  =>  $moderate, 'labels'    =>  Config::get("dict.labels")]);

        return response()->json(['success', $html->render()]);
    }

    public function update(Request $request)
    {
        $updates_fields =   array(  'fname'                     =>  trim($request->input('input_fname')),
                                    'lname'                     =>  trim($request->input('input_lname')),
                                    'mname'                     =>  trim($request->input('input_mname')),
                                    'address'                   =>  trim($request->input('input_address')),
                                    'room'                      =>  trim($request->input('input_room')),
                                    'phone'                     =>  trim($request->input('input_phone')),
                                    'ip_phone'                  =>  trim($request->input('input_ip_phone')),
                                    'birthday'                  =>  trim($request->input('input_birthday')),
                                    'mobile_phone'              =>  trim($request->input('input_mobile_phone')),
                                    'city_phone'                =>  trim($request->input('input_city_phone')),
                                    'email'                     =>  trim($request->input('input_email')),
                                    'email_secondary'           =>  trim($request->input('input_email_secondary')),
                                    'work_title'                =>  trim($request->input('input_work_title')),
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
                                "input_ip_phone.string"    =>  "IP телефон должен быть строкой символов",
                                "input_ip_phone.max"       =>  "IP телефон не может быть длиннее, чем 4 символа",
                                "input_city_phone.string"    =>  "Городской телефон должен быть строкой символов",
                                "input_city_phone.max"       =>  "Городской телефон не может быть длиннее, чем 18 символов",
                                "input_mobile_phone.string"    =>  "Мобильный телефон должен быть строкой символов",
                                "input_mobile_phone.max"       =>  "Мобильный телефон не может быть длиннее, чем 18 символов",
                                "input_room.string"    =>  "Номер комнаты должен быть строкой символов",
                                "input_room.max"       =>  "Номер комнаты не может быть длиннее, чем 4 символа",
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
            'input_ip_phone'          =>  'nullable|string|max:4',
            'input_room'              =>  'nullable|string|max:4',
            'input_city_phone'        =>  'nullable|string|max:18',
            'input_mobile_phone'      =>  'nullable|string|max:18',
            'input_email'             =>  'nullable|string|email',
            'input_email_secondary'   =>  'nullable|string|email',
            'input_work_title'        =>  'nullable|string|max:255',
        ],  $messages);

        if ($validator->fails()) {
            return response()->json(['error', $validator->errors()]);
        }
        else {

            $work   =   Deps_Peoples::where('people_id',    '=',    Auth::user()->id)->first();

            $ps =   Profiles_Saved::where("user_id",    "=",    Auth::user()->id)->orderBy("id", "desc")->first();
            if($ps) {
                Profiles_Saved_Data::where("ps_id", '=',    $ps->id)->delete();
                $ps->user_informed  =   1;
                $ps->save();
                $ps->delete();
            }
            $ps =   new Profiles_Saved();

            $ps->user_id    =   Auth::user()->id;
            $ps->creator_id    =   Auth::user()->id;
            $ps->save();


            $dep_new    =   $dep_old    =   $moderate  =   null;
            $updated_counter    =   0; //Чтобы не создавать запись об изменениях, когда нет реальных изменений

            foreach($updates_fields as $key =>  $value) {
                $createFlag =   false;

                if($key ==  "birthday") {
                    $birthday_parts =   explode(".",    $value);
                    if(count($birthday_parts)   ==  3) {
                        $value   =   $birthday_parts[2]  .   '-' .   $birthday_parts[1]  .   '-' .   $birthday_parts[0];
                    }
                }
                if($key ==  "work_title") {
                    if(!is_null($work)  &&  ($work->work_title   !=  $value)) {
                        $createFlag =   true;
                    }
                }
                if($key    !=  "work_title"    &&  ($value   != Auth::user()->$key)) {
                    $createFlag = true;
                }
                if($createFlag) {
                    $psd =   new Profiles_Saved_Data();

                    $psd->ps_id         =   $ps->id;
                    $psd->creator_id    =   Auth::user()->id;


                    $psd->field_name    =   $key;
                    if(($key    ==  "work_title")  &&  !is_null($work)) {
                        $psd->old_value = $work->$key;
                    }
                    if($key    !=  "work_title") {
                        $psd->old_value = Auth::user()->$key;
                    }
                    $psd->new_value     =   $value;

                    $psd->save();
                    $updated_counter ++;
                }
            }

            if(!$updated_counter) {
                $ps->user_informed  =   1;
                $ps->save();
                $ps->delete();
            }

            $user   =   User::select("users.*", "deps_peoples.work_title",  "deps_peoples.dep_id")
                ->leftJoin('deps_peoples', 'users.id', '=', 'deps_peoples.people_id')
                ->where('users.id', '=', Auth::user()->id)->first();


            if(is_null($moderate)  &&  $user->dep_id) {
                $moderate  =   Dep::getModerate($user->dep_id);
            }

            $psd    =   Profiles_Saved_Data::where("ps_id", '=',    $ps->id)->get();

            $html   =   View::make('profile.viewchanges', [ 'labels' =>  Config::get("dict.labels"),    'psd' =>  $psd,
                                                            'moderate'  =>  $moderate]);

            return response()->json(['success', $html->render(), $updated_counter]);
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

    public function viewtimetable() {
        $currentdate = new DateTime();
        $currentdate->modify("first day of this month");
        if($currentdate->format("N")    !=  1) {
            $currentdate->modify("previous Monday");
        }

        $startDate  =   $currentdate;

        $currentdate = new DateTime();
        $currentdate->modify("last day of this month");
        if($currentdate->format("N")    !=  7) {
            $currentdate->modify("next Sunday");
        }

        $endDate    =   $currentdate;

        var_dump($startDate);
        var_dump($endDate);

        return view('profile.timetableview', [   'labels'    =>  Config::get("dict.labels")]);
    }
}