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
use cijic\phpMorphy\Facade\Morphy;

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
        var_dump(Morphy::getEncoding());
        var_dump(Morphy::getLocale());
        foreach($users as $user) {
            var_dump(Morphy::getPseudoRoot($user->fname));
            var_dump(Morphy::getBaseForm($user->fname));
            var_dump(Morphy::getAllForms($user->fname));
            var_dump(Morphy::getPseudoRoot('Борисовым'));
            var_dump(Morphy::getBaseForm('Борисовым'));
            var_dump(Morphy::getAllForms('Борисовым'));
            var_dump(Morphy::getPseudoRoot('дерево'));
            var_dump(Morphy::getBaseForm('дерево'));
            var_dump(Morphy::getAllForms('дерево'));
            var_dump(Morphy::getPseudoRoot('деревом'));
            var_dump(Morphy::getBaseForm('деревом'));
            var_dump(Morphy::getAllForms('деревом'));

            exit();
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