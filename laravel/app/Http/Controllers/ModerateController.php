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
use App\News;
use PDO;
use Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use DateTime;

class ModerateController extends Controller
{
    public function __construct()
    {
        $this->middleware('moderate');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //новости
        $news = News::orderBy('importancy', 'desc')->limit(5)->get();
        return view('moderate.news.list', ['news'    =>  $news]);
    }

    public function newsedit()
    {

    }

    public function newsdelete()
    {

    }

    public function rooms()
    {

    }

    public function users()
    {

    }

    public function library()
    {

    }

    public function foto()
    {

    }
}