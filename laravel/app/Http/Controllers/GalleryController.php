<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Gallery;
use App\User;
use App\GalleryPhoto;
use DB;
use Auth;

class GalleryController extends Controller
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
        //галереи
        $items = Gallery::orderBy('published_at', 'desc')->get();

        return view('gallery.list', ['items'    =>  $items]);
    }
}
