<?php
/**
 * Created by PhpStorm.
 * User: viach
 * Date: 11.12.2017
 * Time: 10:18
 */

namespace App\Http\Controllers;

use App\Deps_Peoples;
use Auth;
use App\User;
use App\Dep;
use DB;
use App\News;
use App\Rooms;
use App\LibBook;
use App\LibRazdel;
use App\Gallery;
use App\GalleryPhoto;
use App\Dinner_slots;
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
        //$this->middleware('moderate');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        if(Auth::user()->role_id   ==  1) {
            return redirect(route('moderate.users.list'));
        }
        if(Auth::user()->role_id   ==  3) {
            return redirect(route('moderate.users.list'));
        }
        if(Auth::user()->role_id   ==  4) {
            return redirect(route('moderate.news.list'));
        }
        if(Auth::user()->role_id   ==  5) {
            return redirect(route('moderate.rooms.list'));
        }
        if(Auth::user()->role_id   ==  6) {
            return redirect(route('moderate.dinner.list'));
        }
    }

    public function newslist()
    {
        //новости
        $news = News::orderBy('importancy', 'desc')->limit(50)->get();
        return view('moderate.news.list', ['news'    =>  $news]);
    }

    public function newscreate()
    {
        return view('moderate.news.create');
    }

    public function newsedit($id)
    {
        $news = News::findOrFail($id);
        return view('moderate.news.edit', ['news'    =>  $news]);
    }

    public function newsdelete($id)
    {
        $news = News::findOrFail($id);
        $news->delete();

        return redirect(route('moderate.news.list'));
    }

    public function newsstore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'         => 'required|string|max:191',
            'annotation'    =>  'required|string|max:1000',
            'fulltext'      =>  'nullable|string|max:10000',
            'importancy'    =>  'nullable|integer',
        ]);
        if ($validator->fails()) {
            return redirect()->route('moderate.news.create')
                ->withErrors($validator)
                ->withInput();
        }

        $published_at = date("Y-m-d H:i:s");
        if($request->input('published_at')) {
            $published_at = date("Y-m-d H:i:s", strtotime($request->input('published_at') . ":00"));
        }
        $importancy = 1;
        if($request->input('importancy')) {
            $importancy = $request->input('importancy');
        }

        News::create([
            'title'             =>  $request->input('title'),
            'annotation'        =>  $request->input('annotation'),
            'fulltext'          =>  $request->input('fulltext'),
            'published_at'      =>  $published_at,
            'importancy'        =>  $importancy
        ]);

        return redirect(route('moderate.news.list'));
    }

    public function newsupdate(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title'         => 'required|string|max:191',
            'annotation'    =>  'required|string|max:1000',
            'fulltext'      =>  'string|max:10000',
            'importancy'    =>  'integer',
        ]);
        if ($validator->fails()) {
            return redirect()->route('moderate.news.edit')
                ->withErrors($validator)
                ->withInput();
        }

        $news = News::findOrFail($id);
        $news->title        = $request->input('title');
        $news->annotation   = $request->input('annotation');
        $news->fulltext     = $request->input('fulltext');
        $published_at = date("Y-m-d H:i:s");
        if($request->input('published_at')) {
            $published_at = date("Y-m-d H:i:s", strtotime($request->input('published_at') . ":00"));
        }
        $news->published_at = $published_at;
        $importancy = 1;
        if($request->input('importancy')) {
            $importancy = $request->input('importancy');
        }
        $news->importancy  = $importancy;

        $news->updated_at = date("Y-m-d H:i:s");
        $news->save();

        return redirect(route('moderate.news.list'));
    }

    public function dinnerlist()
    {
        //новости
        $items = Dinner_slots::orderBy('time_start', 'asc')->limit(10)->get();
        return view('moderate.dinner.list', ['items'    =>  $items]);
    }

    public function dinnercreate()
    {
        return view('moderate.dinner.create');
    }

    public function dinneredit($id)
    {
        $item = Dinner_slots::findOrFail($id);
        return view('moderate.dinner.edit', ['item'    =>  $item]);
    }

    public function dinnerdelete($id)
    {
        $item = Dinner_slots::findOrFail($id);
        $item->delete();

        return redirect(route('moderate.dinner.list'));
    }

    public function dinnerstore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'          =>  'required|string|max:191',
            'time_start'    =>  'nullable|date_format:H:i',
            'time_end'      =>  'nullable|date_format:H:i',
        ]);
        if ($validator->fails()) {
            return redirect()->route('moderate.dinner.create')
                ->withErrors($validator)
                ->withInput();
        }

        Dinner_slots::create([
            'name'              =>  $request->input('name'),
            'time_start'        =>  $request->input('time_start'),
            'time_end'          =>  $request->input('time_end'),
        ]);

        return redirect(route('moderate.dinner.list'));
    }

    public function dinnerupdate(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name'          =>  'required|string|max:191',
            'time_start'    =>  'nullable|date_format:H:i',
            'time_end'      =>  'nullable|date_format:H:i',
        ]);
        if ($validator->fails()) {
            return redirect()->route('moderate.dinner.edit')
                ->withErrors($validator)
                ->withInput();
        }

        $item = Dinner_slots::findOrFail($id);
        $item->name            = $request->input('name');
        $item->time_start      = $request->input('time_start');
        $item->time_end        = $request->input('time_end');

        $item->save();

        return redirect(route('moderate.dinner.list'));
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
            return redirect()->route('moderate.rooms.create')
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


    public function library()
    {
        $razdels = LibRazdel::selectRaw("lib_razdels.id, name, count(lib_books_razdels.book_id) as numbooks")
            ->leftJoin('lib_books_razdels', 'lib_razdels.id', '=', 'lib_books_razdels.razdel_id')
            ->groupBy(['lib_books_razdels.razdel_id', 'lib_razdels.id', 'lib_razdels.name'])
            ->orderBy('name', 'desc')->get();

        $books = LibBook::orderBy('name', 'desc')->get();

        return view('moderate.library.list', ['razdels'    =>  $razdels, 'books'    =>  $books]);
    }

    public function librarycreate()
    {
        return view('moderate.library.create');
    }

    public function libraryedit($id)
    {
        $razdel = LibRazdel::findOrFail($id);
        return view('moderate.library.edit', ['razdel'    =>  $razdel]);
    }

    public function librarydelete($id)
    {
        $razdel = LibRazdel::findOrFail($id);
        $razdel->delete();
        DB:table('lib_books_razdels')->where("razdel_id", "=", $id)->delete();

        return redirect(route('moderate.library.index'));
    }

    public function libraryupdate($id, Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'name'  => 'required|string|max:128',
        ]);
        if ($validator->fails()) {
            return redirect()->route('moderate.library.edit')
                ->withErrors($validator)
                ->withInput();
        }

        $razdel = LibRazdel::findOrFail($id);
        $razdel->name  = $request->input('name');
        $razdel->updated_at = date("Y-m-d H:i:s");
        $razdel->save();

        return redirect(route('moderate.library.index'));
    }

    public function librarystore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'  => 'required|string|max:128',
        ]);
        if ($validator->fails()) {
            return redirect()->route('moderate.library.create')
                ->withErrors($validator)
                ->withInput();
        }

        LibRazdel::create([
            'name'      => $request->input('name'),
        ]);

        return redirect(route('moderate.library.index'));
    }

    public function librarycreatebook()
    {
        $razdels = LibRazdel::orderby('name')->get();
        return view('moderate.library.createbook', ['razdels'   =>  $razdels]);
    }

    public function libraryeditbook($id)
    {
        $book       = LibBook::findOrFail($id);
        $razdels    = LibRazdel::orderby('name')->get();
        $razdel_ids = DB::table('lib_books_razdels')->where("book_id", "=", $id)->pluck("razdel_id");
        $ids = array();
        foreach($razdel_ids as $razdel_id) {
            $ids[] = $razdel_id;
        }
        return view('moderate.library.editbook', ['book'    =>  $book, 'razdels'    =>  $razdels, 'razdel_ids'  =>  $ids]);
    }

    public function librarydeletebook($id)
    {
        $book = LibBook::findOrFail($id);
        $book->delete();
        DB::table('lib_books_razdels')->where("book_id", "=", $id)->delete();

        return redirect(route('moderate.library.index'));
    }

    public function libraryupdatebook($id, Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'name'      =>  'required|string|max:255',
            'authors'   =>  'required|string|max:255',
            'anno'      =>  'string|max:1000',
            'year'      =>  'integer',
        ]);
        if ($validator->fails()) {
            return redirect()->route('moderate.library.editbook')
                ->withErrors($validator)
                ->withInput();
        }

        $book = LibBook::findOrFail($id);
        $book->name     = $request->input('name');
        $book->authors  = $request->input('authors');
        $book->anno     = $request->input('anno');
        $book->year     = $request->input('year');
        $book->updated_at = date("Y-m-d H:i:s");
        $book->save();

        DB::table('lib_books_razdels')->where("book_id", "=", $id)->delete();
        if(count($request->input('razdels.*'))) {
            foreach($request->input('razdels.*') as $razdel_id) {
                DB::table('lib_books_razdels')->insert(
                    ['razdel_id' => $razdel_id, 'book_id' => $id]
                );
            }
        }

        return redirect(route('moderate.library.index'));
    }

    public function librarystorebook(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'      =>  'required|string|max:255',
            'authors'   =>  'required|string|max:255',
            'anno'      =>  'string|max:1000',
            'year'      =>  'integer',
        ]);
        if ($validator->fails()) {
            return redirect()->route('moderate.library.createbook')
                ->withErrors($validator)
                ->withInput();
        }


        $book = LibBook::create([
                    'name'      => $request->input('name'),
                    'authors'   => $request->input('authors'),
                    'anno'      => $request->input('anno'),
                    'year'      => $request->input('year'),
                ]);

        if(count($request->input('razdels.*'))) {
            foreach($request->input('razdels.*') as $razdel_id) {
                DB::table('lib_books_razdels')->insert(
                    ['razdel_id' => $razdel_id, 'book_id' => $book->id]
                );
            }
        }
        $res_cover  = $this->librarystorebookcover($book->id, $request);
        $res_book   = $this->librarystorebookfile($book->id, $request);

        if($res_cover[0] == "ok") {
            $book->image = $res_cover[1];
        }
        if($res_book[0] == "ok") {
            $book->file = $res_book[2];
        }
        $book->save();
        return redirect(route('moderate.library.index'));
    }

    public function libraryupdatebookcover($id, Request $request)
    {
        $res = $this->librarystorebookcover($id, $request);
        return response()->json([$res[0], $res[1]]);
    }

    public function libraryupdatebookfile($id, Request $request)
    {
        $res = $this->librarystorebookfile($id, $request);
        return response()->json([$res[0], $res[1]]);
    }

    private function librarystorebookcover($id, $request)
    {
        $path   =   Storage::disk('public')->putFile(Config::get('image.cover_path'), $request->file('cover'), 'public');
        $size   =   Storage::disk('public')->getSize($path);
        $type   =   Storage::disk('public')->getMimetype($path);

        if($size <= 3000000) {
            if($type == "image/jpeg" || $type == "image/pjpeg" || $type == "image/png") {
                $manager = new ImageManager(array('driver' => 'imagick'));
                $image  = $manager->make(storage_path('app/public') . '/' . $path)->fit(Config::get('image.cover_width'), Config::get('image.cover_height'))->save(storage_path('app/public') . '/' . $path);
                DB::table('lib_books')->where("id", "=", $id)
                    ->update(['image' => Storage::disk('public')->url($path), 'updated_at' => date("Y-m-d H:i:s")]);
                return array('ok', Storage::disk('public')->url($path));
            }
            else {
                return array('error', 'file wrong type');
            }
        }
        else {
            return array('error', 'file too large');
        }
    }

    private function librarystorebookfile($id, $request)
    {
        $path   =   Storage::disk('public')->putFile(Config::get('image.book_path'), $request->file('book_file'), 'public');

        DB::table('lib_books')->where("id", "=", $id)
            ->update(['file' => Storage::disk('public')->url($path), 'updated_at' => date("Y-m-d H:i:s")]);

        $html = "<a href=\"" .  Storage::disk('public')->url($path) . "\" id=\"link_file\" aria-describedby=\"filelinkHelpInline\">" . Storage::disk('public')->url($path) . "</a>";
        $html .= "<small id=\"filelinkHelpInline\" class=\"text-muted\"><a href=\"" . route('moderate.library.deletebookfile', ["id"    =>  $id]) . "\" id=\"delete_file\">Удалить</a></small>";
        return array('ok', $html, Storage::disk('public')->url($path));
    }

    public function librarydeletebookcover($id)
    {
        $default = Config::get('image.default_cover');
        DB::table('lib_books')->where("id", "=", $id)
            ->update(['image' => $default, 'updated_at' => date("Y-m-d H:i:s")]);

        return response()->json(['ok', $default]);
    }

    public function librarydeletebookfile($id)
    {
        $book = LibBook::findOrFail($id);
        $book->file = null;
        $book->updated_at = date("Y-m-d H:i:s");
        $book->save();

        $html = "<span id=\"nofile\">Нет</span>";
        return response()->json(['ok', $html]);
    }

    public function foto()
    {
        $gallery = Gallery::selectRaw("gallery.id, name, count(gallery_photos.id) as numphotos")
            ->leftJoin('gallery_photos', 'gallery.id', '=', 'gallery_photos.gallery_id')
            ->groupBy(['gallery_photos.gallery_id', 'gallery.id', 'gallery.name'])
            ->orderBy('published_at', 'desc')->get();

        return view('moderate.gallery.list', ['galleries'    =>  $gallery]);
    }

    public function fotocreate()
    {
        return view('moderate.gallery.create');
    }

    public function fotoedit($id)
    {
        $gallery    = Gallery::findOrFail($id);
        $photos     = GalleryPhoto::where("gallery_id", "=", $id);
        return view('moderate.gallery.edit', ["gallery" =>  $gallery, 'photos'  =>  $photos]);
    }

    public function fotostore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'  => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->route('moderate.foto.create')
                ->withErrors($validator)
                ->withInput();
        }

        $gallery    =   Gallery::create([
                            'name'      => $request->input('name'),
                        ]);

        if($request->input('published_at')) {
            $gallery->published_at = date("Y-m-d", strtotime($request->input('published_at')));
        }

        $gallery->save();

        return redirect(route('moderate.foto.edit', ["id"   =>  $gallery->id]));
    }

    public function fotoupdate($id, Request $request)
    {

    }

    public function fotodelete($id, Request $request)
    {
        $item = Gallery::findOrFail($id);
        $item->delete();

        return redirect(route('moderate.foto.index'));
    }

    public function fotodeleteimage($photo_id)
    {
        $photo     = GalleryPhoto::findOrFail($photo_id);
        $photo->delete();

        return redirect(route('moderate.foto.edit', ["id"   =>  $photo->gallery_id]));
    }

    public function users($letter = "А")
    {
        $users = User::where("lname", "LIKE", "$letter%")->orderBy('lname', 'asc')->orderBy('fname', 'asc')->get();

        return view('moderate.users.list', ['users'    =>  $users]);
    }

    public function usersedit($id)
    {
        $user       =   User::findOrFail($id);
        $deps       =   Dep::whereNotNull("parent_id")->orderBy("parent_id")->orderByRaw("LENGTH(parent_id)")->get();
        $works      =   Deps_Peoples::where("people_id",    "=",    $id)->get();

        return view('moderate.users.edit', ['user'    =>  $user,    'works' =>  $works, 'deps'  =>  $deps]);
    }

    public function usersupdate($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'numpark'           =>  'nullable|integer',
            'role_id'           =>  'required|integer',
            'position_desc'     =>  'nullable|string|max:255',
            'lname'             =>  'string|max:255|required',
            'fname'             =>  'string|max:255|required',
            'mname'             =>  'nullable|string|max:255',
            'phone'             =>  'nullable|string|max:3',
            'city_phone'        =>  'nullable|string|max:15',
            'mobile_phone'      =>  'nullable|string|max:18',
            'email'             =>  'nullable|string|email',
            'email_secondary'   =>  'nullable|string|email',
        ]);
        if ($validator->fails()) {
            return redirect()->route('moderate.users.edit', ["id"   =>  $id])
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::findOrFail($id);
        if($request->input('workstart')) {
            $user->workstart = date("Y-m-d", strtotime($request->input('workstart')));
        }
        if($request->input('birthday')) {
            $user->birthday = date("Y-m-d", strtotime($request->input('birthday')));
        }

        $user->role_id          =   $request->input('role_id');

        $user->lname            =   $request->input('lname');
        $user->fname            =   $request->input('fname');
        $user->mname            =   $request->input('mname');

        $user->email            =   $request->input('email');
        $user->email_secondary  =   $request->input('email_secondary');
        $user->phone            =   $request->input('phone');
        $user->city_phone       =   $request->input('city_phone');
        $user->mobile_phone     =   $request->input('mobile_phone');

        $user->numpark          =   $request->input('numpark');
        $user->position_desc    =   $request->input('position_desc');
        $user->updated_at = date("Y-m-d H:i:s");

        $user->save();

        Deps_Peoples::where("people_id",   "=",    $id)->delete();
        $work_titles    =   $request->get('work_title');
        $chefs          =   $request->get('chef');

        $max        =    2;
        $deps       =   Dep::selectRaw("MAX(LENGTH(parent_id)) as max")->first();
        $max        =   $deps["max"];


        foreach($request->input('dep') as $key => $value) {
            $dep_id =   $value;
            $work   =   $work_titles[$key];
            $chef   =   0;
            if(isset($chefs[$key])) {
                $chef=  $chefs[$key];
            }
            $dp =   new Deps_Peoples();

            $dp->people_id      =   $id;
            $dp->dep_id         =   $value;
            $dp->work_title     =   $work;

            if($chef) {
                $curDep     =   Dep::findOrFail($value);
                $cur_length =   mb_strlen($curDep["parent_id"], "UTF-8");
                $chef       =  $max - $cur_length;
            }
            $dp->chef           =   $chef;
            $dp->save();

            $user->name =   $user->lname    .   " " .   mb_substr($user->fname, 0,  1)  .   ".";
            if($user->mname) {
                $user->name =   $user->name .   mb_substr($user->mname, 0,  1)  .   ".";
            }
            if($work)   {
                $user->name =   $user->name . " - " .   $work;
            }

            $user->save();
        }
        return redirect(route('moderate.users.start'));
    }

    public function usersupdateavatar($id, Request $request)
    {
        $path   =   Storage::disk('public')->putFile(Config::get('image.avatar_path'), $request->file('avatar'), 'public');
        $size   =   Storage::disk('public')->getSize($path);
        $type   =   Storage::disk('public')->getMimetype($path);

        if($size <= 3000000) {
            if($type == "image/jpeg" || $type == "image/pjpeg" || $type == "image/png") {
                $manager = new ImageManager(array('driver' => 'imagick'));
                $image  = $manager->make(storage_path('app/public') . '/' . $path)->fit(Config::get('image.avatar_width'))->save(storage_path('app/public') . '/' . $path);
                DB::table('users')->where("id", "=", $id)
                    ->update(['avatar' => Storage::disk('public')->url($path), 'updated_at' => date("Y-m-d H:i:s")]);
                return response()->json(['ok', Storage::disk('public')->url($path)]);
            }
            else {
                return response()->json(['error', 'file wrong type']);
            }
        }
        else {
            return response()->json(['error', 'file too large']);
        }

    }

    public function usersdeleteavatar($id)
    {
        $default = Config::get('image.default_avatar');
        DB::table('users')->where("id", "=", $id)
            ->update(['avatar' => $default, 'updated_at' => date("Y-m-d H:i:s")]);

        return response()->json(['ok', $default]);
    }

    public function adminslist() {

    }

    public function adminscreate() {

    }

    public function adminsstore(Request $request) {

    }

    public function adminsedit($id) {

    }

    public function adminsupdate($id, Request $request) {

    }

    public function adminsdelete($id) {

    }
}