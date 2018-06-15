<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Adldap\Laravel\Facades\Adldap;
use App\News;
use App\User;
use App\LibBook;
use App\LibRazdel;
use DB;

class SearchController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $news = $users = $docs = $books = array();
        return view('search.all', ["news"   =>  $news, "users"  =>  $users, "docs"  => $docs, "books"  => $books]);
    }
}
