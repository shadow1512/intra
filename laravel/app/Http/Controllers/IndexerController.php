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
use App\News;
use App\LibBook;
use App\LibRazdel;
use App\Terms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DateTime;

class IndexerController extends Controller
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

        $users = User::orderBy('name', 'asc')
            ->leftJoin('deps_peoples', 'users.id', '=', 'deps_peoples.people_id')
            ->select('users.*', 'deps_peoples.work_title as work_title')->get();
        foreach($users as $user) {
            $term = new Terms();
            $term->baseterm = Morphy::getPseudoRoot($user->fname);
            $term->term = $user->fname;
            $term->section = 'users';
            $term->record = $user->id;
            $term->save();
        }
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
}