<?php

namespace App\Http\Controllers;

use App\LibBook;
use App\LibRazdel;
use Illuminate\Support\Facades\DB;

class LibraryController extends Controller
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
    public function index($id = null)
    {
        $curRazdel  = null;
        $books      = array();

        if(!is_null($id)) {
            $curRazdel = LibRazdel::findOrFail($id);
            $books = LibBook::leftJoin('lib_books_razdels', 'lib_books.id', '=', 'lib_books_razdels.book_id')
                ->where('lib_books_razdels.razdel_id', '=', $id)
                ->orderBy('name', 'desc')->paginate(8);
            $books->withPath('/library/razdel/'    .   $id);
        }
        else {
            $books = LibBook::orderBy('name', 'desc')->paginate(8);
            $books->withPath('/library/');
        }
        //разделы
        $razdels = LibRazdel::selectRaw("lib_razdels.id, name, count(lib_books_razdels.book_id) as numbooks")
        ->leftJoin('lib_books_razdels', 'lib_razdels.id', '=', 'lib_books_razdels.razdel_id')
            ->groupBy(['lib_books_razdels.razdel_id', 'lib_razdels.id', 'lib_razdels.name'])
            ->orderBy('name', 'desc')->get();



        return view('library.main', [   'razdels'       =>  $razdels,
                                        'curRazdel'     =>  $curRazdel,
                                        'books'         =>  $books]);
    }
}
