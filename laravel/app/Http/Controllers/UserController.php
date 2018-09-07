<?php
/**
 * Created by PhpStorm.
 * User: viach
 * Date: 11.12.2017
 * Time: 10:18
 */

namespace App\Http\Controllers;

use App\User;
use App\Dep;
use DB;
use PDO;
use Auth;

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
            ->select('users.*', 'deps.name as depname', 'deps.id as depid')
            ->leftJoin('deps', 'deps_peoples.dep_id', '=', 'deps.id')
            ->findOrFail($id);

        $contacts   =   array();

        if(Auth::check()) {
            $contacts = User::select("users.id", "users.avatar", "users.fname", "users.lname", "users.mname", "users.position", "users.email", "users.phone")
                ->leftJoin('user_contacts', 'user_contacts.contact_id', '=', 'users.id')->where('user_contacts.user_id', '=', Auth::user()->id)->get();
        }


        return view('users.unit', ['user'    =>  $user, 'contacts'  =>  $contacts]);
    }

    protected function getCrumbs($id) {
        $crumbs = array();
        $currentDep     = Dep::findOrFail($id);
        $parent = $currentDep->parent_id;
        $length = mb_strlen($parent, "UTF-8");
        if($length > 2) {
            $parent = mb_substr($parent, 0, $length - 2);
            $dep = Dep::where('parent_id', '=', $parent)->firstOrFail();
            $crumbs[] = $dep;
            $length = $length - 2;
        }
        return $crumbs;
    }

    public function search($id = null)
    {
        $rootdeps           = array();
        $counts             = array();
        $directory_name     = "Консорциум Кодекс";
        $currentDep         = null;
        $crumbs             = array();

        if(!is_null($id)) {
            $currentDep     = Dep::findOrFail($id);
            $users = User::leftJoin('deps_peoples', 'users.id', '=', 'deps_peoples.people_id')
                ->select('users.*', 'deps.name as depname', 'deps.id as depid', 'deps_peoples.work_title')
                ->leftJoin('deps', 'deps_peoples.dep_id', '=', 'deps.id')
                ->whereRaw("deps_peoples.dep_id IN (SELECT id FROM deps WHERE parent_id LIKE '" . $currentDep->parent_id . "%')")
                ->orderBy('users.name', 'asc')
                ->limit(20)->get();
        }
        else {
            $users = User::leftJoin('deps_peoples', 'users.id', '=', 'deps_peoples.people_id')
                ->select('users.*', 'deps.name as depname', 'deps.id as depid', 'deps_peoples.work_title')
                ->leftJoin('deps', 'deps_peoples.dep_id', '=', 'deps.id')
                ->orderBy('users.name', 'asc')
                ->limit(20)->get();
        }

        if(!is_null($id)) {
            $crumbs = $this->getCrumbs($id);
            $length         = mb_strlen($currentDep->parent_id, "UTF-8") + 2;
            $directory_name = $currentDep->name;
            $rootdeps = Dep::where('parent_id', 'LIKE', $currentDep->parent_id . "%")
                ->where(DB::raw('LENGTH(parent_id) = ' . $length))
                ->where('logical', '=', 1)
                ->orderBy('name', 'asc')->get();
            if(count($rootdeps)) {
                foreach($rootdeps as $rdep) {
                    $curlength     = mb_strlen($rdep->parent_id, "UTF-8") + 2;
                    $deps[$rdep->id] = Dep::where('parent_id', 'LIKE', $rdep->parent_id . "%")
                        ->where(DB::raw('LENGTH(parent_id) = ' . $curlength))
                        ->orderBy('name', 'asc')
                        ->get();

                    foreach($deps[$rdep->id] as $dep) {
                        $nums = DB::selectOne("SELECT SUM(t.numpeople) as sum FROM (SELECT COUNT(DISTINCT people_id) as numpeople FROM deps_peoples WHERE dep_id IN (SELECT id FROM deps WHERE parent_id LIKE '" . $dep->parent_id . "%') GROUP BY dep_id) t");
                        $counts[$dep->id] = $nums->sum;
                    }
                }
            }
            else {
                $deps = Dep::where('parent_id', 'LIKE', $currentDep->parent_id . "%")
                    ->whereRaw('LENGTH(parent_id) = ' . $length)
                    ->orderBy('name', 'asc')
                    ->get();

                if(count($deps)) {
                    foreach ($deps as $dep) {
                        $nums = DB::selectOne("SELECT SUM(t.numpeople) as sum FROM (SELECT COUNT(DISTINCT people_id) as numpeople FROM deps_peoples WHERE dep_id IN (SELECT id FROM deps WHERE parent_id LIKE '" . $dep->parent_id . "%') GROUP BY dep_id) t");
                        $counts[$dep->id] = $nums->sum;
                    }
                }
                else {
                    $newparent = mb_substr($currentDep->parent_id, 0, mb_strlen($currentDep->parent_id, "UTF-8") - 2, "UTF-8");
                    $length -= 2;

                    $deps = Dep::where('parent_id', 'LIKE', $newparent . "%")
                        ->whereRaw('LENGTH(parent_id) = ' . $length)
                        ->orderBy('name', 'asc')
                        ->get();

                    foreach ($deps as $dep) {
                        $nums = DB::selectOne("SELECT SUM(t.numpeople) as sum FROM (SELECT COUNT(DISTINCT people_id) as numpeople FROM deps_peoples WHERE dep_id IN (SELECT id FROM deps WHERE parent_id LIKE '" . $dep->parent_id . "%') GROUP BY dep_id) t");
                        $counts[$dep->id] = $nums->sum;
                    }
                }
            }
        }
        else {
            $length = 4;
            $deps = Dep::whereRaw('LENGTH(parent_id) = ' . $length)
                ->orderBy('name', 'asc')
                ->get();

            foreach($deps as $dep) {
                $nums = DB::selectOne("SELECT SUM(t.numpeople) as sum FROM (SELECT COUNT(DISTINCT people_id) as numpeople FROM deps_peoples WHERE dep_id IN (SELECT id FROM deps WHERE parent_id LIKE '" . $dep->parent_id . "%') GROUP BY dep_id) t");
                $counts[$dep->id] = $nums->sum;
            }
        }




        return view('users.search', [   "crumbs"            =>  $crumbs,
                                        "directory_name"    =>  $directory_name,
                                        "startsearch"       =>  true,
                                        "users"             =>  $users,
                                        "rootdeps"          =>  $rootdeps,
                                        "deps"              =>  $deps,
                                        "counts"            =>  $counts,
                                        "currentDep"        =>  $currentDep]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
}