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
use App\Rooms;
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

    public function newscreate()
    {

    }

    public function newsedit()
    {

    }

    public function newsdelete()
    {

    }

    public function rooms()
    {
        //Комнаты
        $rooms = Rooms::orderBy('name')->get();
        return view('moderate.rooms.list', ['rooms'    =>  $rooms]);
    }

    public function roomscreate()
    {
        return view('moderate.rooms.create');
    }

    public function roomsedit($id)
    {
        $room = Rooms::findOrFail($id);
        return view('moderate.rooms.edit', ['room'    =>  $room]);
    }

    public function roomsdelete($id)
    {
        $room = Rooms::findOrFail($id);
        $room->delete();

        return redirect(route('moderate.rooms.index'));
    }

    public function roomsstore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'  => 'required|string|max:50',
        ]);
        if ($validator->fails()) {
            return redirect('moderate.rooms.create')
                ->withErrors($validator)
                ->withInput();
        }

        $available = 0;
        if($request->input('available')) {
            $available = 0;
        }
        else {
            $available = 1;
        }

        Rooms::create([
            'name'      => $request->input('name'),
            'available' =>  $available,
        ]);

        return redirect(route('moderate.rooms.index'));
    }

    public function roomsupdate(Request $request, $id)
    {
        //
        $validator = Validator::make($request->all(), [
            'name'  => 'required|string|max:50',
        ]);
        if ($validator->fails()) {
            return redirect()->route('moderate.rooms.edit')
                ->withErrors($validator)
                ->withInput();
        }

        $room = Rooms::findOrFail($id);
        $room->name  = $request->input('name');
        if($request->input('available')) {
            $room->available = 0;
        }
        else {
            $room->available = 1;
        }
        $room->updated_at = date("Y-m-d H:i:s");
        $room->save();

        return redirect(route('moderate.rooms.index'));
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