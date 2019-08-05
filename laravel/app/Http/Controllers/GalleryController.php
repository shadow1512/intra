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
        $items = Gallery::selectRaw("gallery.*, count(gallery_photos.id) as num")
            ->leftJoin('gallery_photos',    'gallery.id',   '=',    'gallery_photos.gallery_id')
            ->whereNull('gallery_photos.deleted_at')
            ->groupBy('gallery.id')
            ->orderBy('published_at', 'desc')->paginate(10);

        $photos_by_gallery  =   array();
        foreach($items as $item) {
            $photo =   GalleryPhoto::where('gallery_id',   '=',    $item->id)->limit(1)->first();
            $photos_by_gallery[$item->id]   =   $photo;
        }

        $items->withPath('/foto/');

        return view('gallery.list', ['items'    =>  $items, 'photos'    =>  $photos_by_gallery]);
    }
}
