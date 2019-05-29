<?php

namespace App\Http\Controllers;

use App\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DateTime;

class NewsController extends Controller
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
        //новости
        $news = News::orderBy('importancy', 'desc')->get()->paginate(25);
        $news->withParam('/news/');

        return view('news.fulllist', ['news'    =>  $news]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function item($id)
    {
        $item = News::findOrFail($id);
        return view('news.item', ['item'    =>  $item]);
    }
}
