<?php
/**
 * Created by PhpStorm.
 * User: viach
 * Date: 11.12.2017
 * Time: 10:18
 */

namespace App\Http\Controllers;

use App\Deps_Peoples;
use App\Profiles_Saved_Data;
use Auth;
use App\User;
use App\Profiles_Saved;
use App\Dep;
use DB;
use App\News;
use App\Rooms;
use App\Booking;
use App\LibBook;
use App\LibRazdel;
use App\Gallery;
use App\GalleryPhoto;
use App\Dinner_slots;
use PDO;
use Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use DateTime;
use Mail;

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
            return redirect(route('moderate.users.start'));
        }
        if(Auth::user()->role_id   ==  3) {
            return redirect(route('moderate.users.start'));
        }
        if(Auth::user()->role_id   ==  4) {
            return redirect(route('moderate.news.list'));
        }
        if(Auth::user()->role_id   ==  5) {
            return redirect(route('moderate.rooms.index'));
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
        $messages   =   array(
            "title.required"                =>  "Поле обязательно для заполнения",
            "title.max"                     =>  "Поле не должно быть длиннее, чем 191 символ",
            "annotation.required"           =>  "Поле обязательно для заполнения",
            "annotation.max"                =>  "Поле не должно быть длиннее, чем 1000 символов",
            "fulltext.max"                  =>  "Поле не должно быть длиннее, чем 10000 символов",
            "importancy.integer"            =>  "Поле должно содержать целое число"

        );

        $validator = Validator::make($request->all(), [
            'title'         => 'required|string|max:191',
            'annotation'    =>  'required|string|max:1000',
            'fulltext'      =>  'nullable|string|max:10000',
            'importancy'    =>  'nullable|integer',
        ],  $messages);

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
        $messages   =   array(
            "title.required"                =>  "Поле обязательно для заполнения",
            "title.max"                     =>  "Поле не должно быть длиннее, чем 191 символ",
            "annotation.required"           =>  "Поле обязательно для заполнения",
            "annotation.max"                =>  "Поле не должно быть длиннее, чем 1000 символов",
            "fulltext.max"                  =>  "Поле не должно быть длиннее, чем 10000 символов",
            "importancy.integer"            =>  "Поле должно содержать целое число"

        );

        $validator = Validator::make($request->all(), [
            'title'         => 'required|string|max:191',
            'annotation'    =>  'required|string|max:1000',
            'fulltext'      =>  'string|max:10000',
            'importancy'    =>  'integer',
        ],  $messages);

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
        $messages   =   array(
            "name.required"             =>  "Поле обязательно для заполнения",
            "name.max"                  =>  "Поле не должно быть длиннее, чем 191 символ",
            "time_start.required"       =>  "Поле обязательно для заполнения",
            "time_end.required"         =>  "Поле обязательно для заполнения",
            "time_start.date_format"    =>  "Поле должно быть заполнено в формате ЧЧ:ММ",
            "time_end.date_format"      =>  "Поле должно быть заполнено в формате ЧЧ:ММ",
        );

        $validator = Validator::make($request->all(), [
            'name'          =>  'required|string|max:191',
            'time_start'    =>  'required|date_format:H:i',
            'time_end'      =>  'required|date_format:H:i',
        ],  $messages);
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
        $messages   =   array(
            "name.required"             =>  "Поле обязательно для заполнения",
            "name.max"                  =>  "Поле не должно быть длиннее, чем 191 символ",
            "time_start.date_format"    =>  "Поле должно быть заполнено в формате ЧЧ:ММ",
            "time_end.date_format"      =>  "Поле должно быть заполнено в формате ЧЧ:ММ",
            "time_start.required"       =>  "Поле обязательно для заполнения",
            "time_end.required"         =>  "Поле обязательно для заполнения",
        );

        $validator = Validator::make($request->all(), [
            'name'          =>  'required|string|max:191',
            'time_start'    =>  'required|date_format:H:i',
            'time_end'      =>  'required|date_format:H:i',
        ],  $messages);
        if ($validator->fails()) {
            return redirect()->route('moderate.dinner.edit',    ["id"   =>  $id])
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
        $rooms = Rooms::selectRaw('rooms.*, count(room_bookings.id) as numbookings')
            ->leftJoin('room_bookings', function($join) {
                $join->on('rooms.id', '=', 'room_bookings.room_id')->where('room_bookings.approved',   '=',    0)->whereNull('room_bookings.deleted_at');
            })
            ->groupBy(['room_bookings.room_id',  'rooms.id'])
            ->orderBy('name', 'desc')->get();

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
        $messages   =   array(
            "name.required"             =>  "Поле обязательно для заполнения",
            "name.max"                  =>  "Поле не должно быть длиннее, чем 50 символов",
            "notify_email.email"        =>  "Поле должно быть формата email",
            "notify_email.max"          =>  "Поле не должно быть длиннее, чем 255 символов",
        );
        $validator = Validator::make($request->all(), [
            'name'  => 'required|string|max:50',
            'notify_email'  =>  'nullable|string|max:255|email',
        ],  $messages);
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
        $messages   =   array(
            "name.required"             =>  "Поле обязательно для заполнения",
            "notify_email.email"        =>  "Поле должно быть формата email",
            "notify_email.max"          =>  "Поле не должно быть длиннее, чем 255 символов",
            "name.max"                  =>  "Поле не должно быть длиннее, чем 50 символов",
        );

        $validator = Validator::make($request->all(), [
            'name'          => 'required|string|max:50',
            'notify_email'  =>  'nullable|string|max:255|email'
        ],  $messages);
        if ($validator->fails()) {
            return redirect()->route('moderate.rooms.edit', ["id"   =>  $id])
                ->withErrors($validator)
                ->withInput();
        }

        $room = Rooms::findOrFail($id);
        $room->name             = $request->input('name');
        $room->notify_email     = $request->input('notify_email');
        if($request->input('available')) {
            $room->available = 0;
        }
        else {
            $room->available = 1;
        }
        $room->save();

        return redirect(route('moderate.rooms.index'));
    }

    public function bookingslist($id) {
        $room = Rooms::findOrFail($id);
        if(empty($room->available)) {
            $bookings = Booking::select('room_bookings.*',
                'users.phone as phone', 'users.email as email', 'users.fname as fname',  'users.lname as lname', 'users.mname as mname')
                ->leftJoin("users", 'room_bookings.user_id', '=', 'users.id')
                ->where('room_id',  '=',    $id)
                ->where('approved', '=',    0)
                ->orderBy('date_book')
                ->orderBy('time_start')
                ->get();

            return view('moderate.rooms.bookingslist', ['bookings'    =>  $bookings,    'room'  =>  $room]);
        }
    }

    public function bookingconfirm($id) {

        $booking    = Booking::findOrFail($id);
        $room       = Rooms::findOrFail($booking->room_id);
        $user       = User::findOrFail($booking->user_id);

        if(empty($room->available)) {
            $booking->approved  =   1;
            $booking->save();
            if($user->email) {
                Mail::send('emails.bookingapproved', ['booking' => $booking], function ($m) use ($user, $booking) {
                    $m->from('newintra@kodeks.ru', 'Новый корпоративный портал');
                    $m->to($user->email, $user->fname)->subject('Ваше бронирование ' .   $booking->name    .   " подтверждено");
                });

            }
        }

        return redirect(route('moderate.rooms.index'));
    }

    public function bookingdecline($id){
        $booking    = Booking::findOrFail($id);
        $room       = Rooms::findOrFail($booking->room_id);
        $user       = User::findOrFail($booking->user_id);

        if(empty($room->available)) {
            return view('moderate.rooms.bookingdeclinewithreason', ['booking'    =>  $booking,    'room'  =>  $room,    'user'  =>  $user]);
        }
        else {
            return redirect(route('moderate.rooms.index'));
        }
    }


    public function bookingdeclinewithreason($id, Request $request) {
        $booking    = Booking::findOrFail($id);
        $room       = Rooms::findOrFail($booking->room_id);
        $user       = User::findOrFail($booking->user_id);

        if(empty($room->available)) {
            $messages   =   array(
                "reason.required"             =>  "Поле обязательно для заполнения",
                "reason.max"                  =>  "Поле не должно быть длиннее, чем 250 символов",
            );

            $validator = Validator::make($request->all(), [
                'reason'  => 'required|string|max:250',
            ],  $messages);
            if ($validator->fails()) {
                return redirect()->route('moderate.rooms.bookingdeclinewithreason', ['booking'    =>  $booking,    'room'  =>  $room,    'user'  =>  $user])
                    ->withErrors($validator)
                    ->withInput();
            }

            $booking->reason    =   $request->input('reason');
            $booking->save();
            $booking->delete();
            if($user->email) {
                Mail::send('emails.bookingdeclined', ['booking' => $booking, 'room'  =>  $room], function ($m) use ($user, $booking) {
                    $m->from('newintra@kodeks.ru', 'Новый корпоративный портал');
                    $m->to($user->email, $user->fname)->subject('Ваше бронирование ' .   $booking->name    .   " отклонено");
                });

            }

            return redirect(route('moderate.rooms.index'));
        }

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
        $messages   =   array(
            "name.required"             =>  "Поле обязательно для заполнения",
            "name.max"                  =>  "Поле не должно быть длиннее, чем 128 символов",
        );
        $validator = Validator::make($request->all(), [
            'name'  => 'required|string|max:128',
        ],  $messages);
        if ($validator->fails()) {
            return redirect()->route('moderate.library.edit',   ["id"   =>  $id])
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
        $messages   =   array(
            "name.required"             =>  "Поле обязательно для заполнения",
            "name.max"                  =>  "Поле не должно быть длиннее, чем 128 символов",
        );
        $validator = Validator::make($request->all(), [
            'name'  => 'required|string|max:128',
        ],  $messages);
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
        $messages   =   array(
            "name.required"             =>  "Поле обязательно для заполнения",
            "name.max"                  =>  "Поле не должно быть длиннее, чем 255 символов",
            "authors.required"          =>  "Поле обязательно для заполнения",
            "authors.max"               =>  "Поле не должно быть длиннее, чем 255 символов",
            "anno.max"                  =>  "Поле не должно быть длиннее, чем 1000 символов",
            "year.integer"              =>  "Поле должно быть целым числом, обозначающим год без других символов"
        );

        $validator = Validator::make($request->all(), [
            'name'      =>  'required|string|max:255',
            'authors'   =>  'required|string|max:255',
            'anno'      =>  'nullable|string|max:1000',
            'year'      =>  'nullable|integer',
        ],  $messages);


        if ($validator->fails()) {
            return redirect()->route('moderate.library.editbook', ["id" =>  $id])
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
        if($request->input('razdels.*') &&  count($request->input('razdels.*'))) {
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
        $messages   =   array(
            "name.required"             =>  "Поле обязательно для заполнения",
            "name.max"                  =>  "Поле не должно быть длиннее, чем 255 символов",
            "authors.required"          =>  "Поле обязательно для заполнения",
            "authors.max"               =>  "Поле не должно быть длиннее, чем 255 символов",
            "anno.max"                  =>  "Поле не должно быть длиннее, чем 1000 символов",
            "year.integer"              =>  "Поле должно быть целым числом, обозначающим год без других символов"
        );

        $validator = Validator::make($request->all(), [
            'name'      =>  'required|string|max:255',
            'authors'   =>  'required|string|max:255',
            'anno'      =>  'nullable|string|max:1000',
            'year'      =>  'nullable|integer',
        ],  $messages);

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

        if($request->input('razdels.*')  &&  count($request->input('razdels.*'))) {
            foreach($request->input('razdels.*') as $razdel_id) {
                DB::table('lib_books_razdels')->insert(
                    ['razdel_id' => $razdel_id, 'book_id' => $book->id]
                );
            }
        }
        /*$res_cover  = $this->librarystorebookcover($book->id, $request);
        $res_book   = $this->librarystorebookfile($book->id, $request);

        if($res_cover[0] == "ok") {
            $book->image = $res_cover[1];
        }
        if($res_book[0] == "ok") {
            $book->file = $res_book[2];
        }*/
        $book->save();
        return redirect(route('moderate.library.editbook',  ["id"   =>  $book->id]));
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
        if(!is_null($request->file('cover'))) {
            $fsize = $request->file('cover')->getSize();
            if ($fsize >= 5000000) {
                return array('error', 'file too large');
            }

            $path   =   Storage::disk('public')->putFile(Config::get('image.cover_path'), $request->file('cover'), 'public');
            $size   =   Storage::disk('public')->getSize($path);
            $type   =   Storage::disk('public')->getMimetype($path);

            if($size <= 5000000) {
                if($type == "image/jpeg" || $type == "image/pjpeg" || $type == "image/png") {
                    $manager = new ImageManager(array('driver' => 'imagick'));
                    $image  = $manager->make(storage_path('app/public') . '/' . $path)->fit(Config::get('image.cover_width'), Config::get('image.cover_height'))->save(storage_path('app/public') . '/' . $path);
                    DB::table('lib_books')->where("id", "=", $id)
                        ->update(['image' => Storage::disk('public')->url($path), 'updated_at' => date("Y-m-d H:i:s")]);

                    $html   =   "<label for=\"img_image\" class=\"col-md-4 control-label\">Обложка</label>";
                    $html   .=  "<img src=\""   .   Storage::disk('public')->url($path)    .   "\" id=\"img_image\" aria-describedby=\"imageimgHelpInline\"/><br/>";
                    $html   .=  "<small id=\"imageimgHelpInline\" class=\"text-muted\"><a href=\""  .   route('moderate.library.deletebookcover', ["id"    =>  $id])  .   "\" id=\"delete_cover\">Удалить</a></small>";
                    return array('ok', $html,   Storage::disk('public')->url($path));
                }
                else {
                    return array('error', 'file wrong type');
                }
            }
            else {
                return array('error', 'file too large');
            }
        }
    }

    private function librarystorebookfile($id, $request)
    {
        if(!is_null($request->file('book_file'))) {
            $fsize = $request->file('book_file')->getSize();
            if ($fsize >= 10000000) {
                return array('error', 'file too large');
            }

            $path = Storage::disk('public')->putFile(Config::get('image.book_path'), $request->file('book_file'), 'public');
            $size = Storage::disk('public')->getSize($path);

            if ($size <= 10000000) {
                DB::table('lib_books')->where("id", "=", $id)
                    ->update(['file' => Storage::disk('public')->url($path), 'updated_at' => date("Y-m-d H:i:s")]);

                $html   =   "<label for=\"link_file\" class=\"col-md-4 control-label\">Исходный файл</label><br/>";
                $html .= "<a href=\"" . Storage::disk('public')->url($path) . "\" id=\"link_file\" aria-describedby=\"filelinkHelpInline\">" . Storage::disk('public')->url($path) . "</a>";
                $html .= "<small id=\"filelinkHelpInline\" class=\"text-muted\"><a href=\"" . route('moderate.library.deletebookfile', ["id" => $id]) . "\" id=\"delete_file\">Удалить</a></small>";
                return array('ok', $html, Storage::disk('public')->url($path));
            } else {
                return array('error', 'file too large');
            }
        }
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
            ->whereNull('gallery_photos.deleted_at')
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
        $photos     = GalleryPhoto::where("gallery_id", "=", $id)->get();

        return view('moderate.gallery.edit', ["gallery" =>  $gallery, 'photos'  =>  $photos]);
    }

    public function fotostore(Request $request)
    {
        $messages   =   array(
            "name.required"             =>  "Поле обязательно для заполнения",
            "name.max"                  =>  "Поле не должно быть длиннее, чем 255 символов",
        );
        $validator = Validator::make($request->all(), [
            'name'  => 'required|string|max:255',
        ],  $messages);

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
        $messages   =   array(
            "name.required"             =>  "Поле обязательно для заполнения",
            "name.max"                  =>  "Поле не должно быть длиннее, чем 255 символов",
        );
        $validator = Validator::make($request->all(), [
            'name'  => 'required|string|max:255',
        ],  $messages);

        if ($validator->fails()) {
            return redirect()->route('moderate.foto.edit',  ["id"   =>  $id])
                ->withErrors($validator)
                ->withInput();
        }

        $gallery    = Gallery::findOrFail($id);

        if($request->input('published_at')) {
            $gallery->published_at = date("Y-m-d", strtotime($request->input('published_at')));
        }
        $gallery->name  =   trim($request->input('name'));

        $gallery->save();

        return redirect(route('moderate.foto.index'));
    }

    public function fotodelete($id, Request $request)
    {
        $item = Gallery::findOrFail($id);

        $photos =   GalleryPhoto::where('gallery_id',   '=',    $item->id)->get();
        foreach($photos as $photo) {
            if(Storage::disk('public')->exists(Config::get('image.gallery_path')   .   '/'  .   $photo->gallery_id   .   '/')) {
                Storage::disk('public')->delete([   Config::get('image.gallery_path')   .   '/'  .   $photo->gallery_id    .   '/' .   $photo->desc,
                    Config::get('image.gallery_path')   .   '/'  .   $photo->gallery_id    .   '/th_' .   $photo->desc]);
            }
            $photo->delete();
        }

        if(Storage::disk('public')->exists(Config::get('image.gallery_path')   .   '/'  .   $id   .   '/')) {
            Storage::disk('public')->deleteDirectory(Config::get('image.gallery_path')   .   '/'  .   $id   .   '/');
        }
        $item->delete();

        return redirect(route('moderate.foto.index'));
    }

    public function fotodeleteimage($photo_id)
    {
        $photo     = GalleryPhoto::findOrFail($photo_id);

        if(Storage::disk('public')->exists(Config::get('image.gallery_path')   .   '/'  .   $photo->gallery_id   .   '/')) {
            Storage::disk('public')->delete([   Config::get('image.gallery_path')   .   '/'  .   $photo->gallery_id    .   '/' .   $photo->desc,
                                                Config::get('image.gallery_path')   .   '/'  .   $photo->gallery_id    .   '/th_' .   $photo->desc]);
        }
        $photo->delete();

        return json_encode(array("files"    =>  array(array(  $photo->desc          =>  true))));
    }

    public function fotoupdateimage($id, Request $request)
    {
        if(!is_null($request->file('photo_files'))) {

            if(!Storage::disk('public')->exists(Config::get('image.gallery_path')   .   '/'  .   $id   .   '/')) {
                Storage::disk('public')->makeDirectory(Config::get('image.gallery_path')   .   '/'  .   $id   .   '/');
            }

            $files      =   $request->file('photo_files');
            $filename   =   microtime()  .   "." .   $files[0]->extension();

            $path           =   Storage::disk('public')->putFileAs(Config::get('image.gallery_path')   .   '/'  .   $id,  $files[0], 'th_'  .   $filename, 'public');
            $path_full      =   Storage::disk('public')->putFileAs(Config::get('image.gallery_path')   .   '/'  .   $id, $files[0], $filename, 'public');
            $size   =   Storage::disk('public')->getSize($path);
            $type   =   Storage::disk('public')->getMimetype($path);

            if($size <= 3000000) {
                if($type == "image/jpeg" || $type == "image/pjpeg" || $type == "image/png") {
                    $manager = new ImageManager(array('driver' => 'imagick'));
                    $image  = $manager->make(storage_path('app/public') . '/' . $path)->fit(Config::get('image.gallery_photo_thumb_width'), Config::get('image.gallery_photo_thumb_height'));
                    $image->save(storage_path('app/public') . '/' . $path);

                    $gallery_image  =   new GalleryPhoto();
                    $gallery_image->gallery_id  =   $id;
                    $gallery_image->image       =   Storage::disk('public')->url($path_full);
                    $gallery_image->image_th    =   Storage::disk('public')->url($path);
                    $gallery_image->desc        =   $filename;
                    $gallery_image->size        =   (float)round($size/1000, 2);

                    $gallery_image->save();

                    return json_encode(array("files"    =>  array(array(  "name"          =>  $filename,
                                                                                "size"          =>  $size,
                                                                                "url"           =>  Storage::disk('public')->url($path_full),
                                                                                "thumbnailUrl"  =>  Storage::disk('public')->url($path),
                                                                                "deleteUrl"     =>  route('moderate.foto.deleteimage',  ["id"   =>  $gallery_image->id]),
                                                                                "deleteType"    =>  "GET"))));
                }
                else {
                    return json_encode(array("files"    =>  array(array(  "name"          =>  $filename,
                        "size"          =>  $size,
                        "error"         =>  "Вы пытаетесь загрузить файл не поддерживаемого типа"))));
                }
            }
            else {
                return json_encode(array("files"    =>  array(array(  "name"          =>  $filename,
                    "size"          =>  $size,
                    "error"         =>  "Размер загружаемого файла превышает установленный предел в 3мб"))));
            }
        }
    }

    public function users($letter = "А")
    {
        $users  =   User::ByModerator(Auth::user()->id)->orderBy('lname', 'asc')->orderBy('fname', 'asc')->get();
        $mode   =   'list';
        if(count($users)    >   50) {
            $mode   =   'letters';
            $users = User::ByModerator(Auth::user()->id)->where("lname", "LIKE", "$letter%")->orderBy('lname', 'asc')->orderBy('fname', 'asc')->get();
        }

        foreach($users as $user) {
            $count  =   Profiles_Saved::where("user_id", "=",    $user->id)->count();
            $user->count_updated    =   $count;
        }
        return view('moderate.users.list', ['users'    =>  $users,  'mode'  =>  $mode]);
    }

    public function usersedit($id)
    {
        $user       =   User::findOrFail($id);
        $work       =   Deps_Peoples::where("people_id",    "=",    $id)->first();

        $dep =  Dep::leftJoin("deps_peoples",   "deps.id",   "=",    "deps_peoples.dep_id")->where("deps_peoples.people_id", "=", $id)->first();
        $ps_record=    Profiles_Saved::where("user_id",    "=",    $id)->orderBy("id",    "desc")->first();
        $psd    =   $dep_old    =   $dep_new    =   null;
        if($ps_record) {
            $psd    =   Profiles_Saved_Data::where('ps_id', '=',    $ps_record->id)->get();
        }

        $moderate   =   null;
        if($dep->id) {
            var_dump($dep->id);
            $moderate   =   Dep::getModerate($dep->id);
            var_dump($moderate->id);
        }
        die();
        if(is_null($moderate)) {
            return redirect(route('moderate.users.start'));
        }
        return view('moderate.users.edit', ['user'    =>  $user,    'work'  =>  $work, 'dep'  =>  $dep,
                                            'ps'    =>  $ps_record, 'psd'   =>  $psd,   'labels'    =>  Config::get("dict.labels")]);
    }

    public function makeFieldChangeUser($psd_id, Request $request) {
        $psd    =   Profiles_Saved_Data::findOrFail($psd_id);
        $ps     =   Profiles_Saved::findOrFail($psd->ps_id);


        $messages   =   array(
            "input_newstatus.required"        =>  "Изменение нужно утвердить или отклонить",
            "input_newstatus.in"              =>  "Неверный новый статус изменения",
            "input_reason.max"                =>  "Поле не должно быть длиннее 255 символов"
        );

        $validator = Validator::make($request->all(), [
            'input_newstatus'           =>  [
                'required',
                Rule::in([2, 3]),
            ],
            'input_reason'            =>  'nullable|string|max:255|',
        ],  $messages);

        if ($validator->fails()) {
            return response()->json(['error', $validator->errors()]);
        }

        $psd->status    =   $request->input('input_newstatus');
        if($psd->status ==  3) {
            $psd->reason    =   trim(rawurldecode($request->input('input_reason')));
        }
        if($psd->status ==  2) {
            $psd->new_value    =   trim(rawurldecode($request->input('input_newval')));
        }
        $psd->save();

        return response()->json(['success']);
    }

    public function commitChangesForUser($ps_id, Request $request) {
        $ps     =   Profiles_Saved::findOrFail($ps_id);
        $user   =   User::findOrFail($ps->user_id);
        $psd    =   Profiles_Saved_Data::where("ps_id", "=",    $ps_id)->get();
        foreach($psd as $item) {
            if($item->status    ==  2) {
                if ($item->field_name != "work_title") {
                    $user->{$item->field_name} = $item->new_value;
                }
                else {
                    $wt_record =   Deps_Peoples::where("people_id", "=", $user->id)->first();
                    if(!$wt_record) {
                        $wt_record = new Deps_Peoples();
                        $wt_record->chef    =   null;
                        $wt_record->people_id  =   $user->id;
                    }
                    $wt_record->work_title  =   $item->new_value;
                    $wt_record->save();
                }
            }
            else {
                $item->status   =   3;
                $item->save();
            }
            $item->delete();
        }

        $user->numupdates   =   $user->numupdates   +   1;
        $user->save();
        $ps->delete();

        return response()->json(['success']);
    }

    public function usersupdate($id, Request $request)
    {
        $user   =   User::leftJoin('deps_peoples',  'users.id', '=',    'deps_peoples.people_id')->whereRaw('users.id=' .   $id)->first();

        $updates_fields =   array(
            'fname'                     =>  trim($request->input('fname')),
            'lname'                     =>  trim($request->input('lname')),
            'mname'                     =>  trim($request->input('mname')),
            'room'                      =>  trim($request->input('room')),
            'phone'                     =>  trim($request->input('phone')),
            'birthday'                  =>  trim($request->input('birthday')),
            'mobile_phone'              =>  trim($request->input('mobile_phone')),
            'city_phone'                =>  trim($request->input('city_phone')),
            'email'                     =>  trim($request->input('email')),
            'email_secondary'           =>  trim($request->input('email_secondary')),
            'numpark'                   =>  trim($request->input('numpark')),
            'workstart'                 =>  trim($request->input('workstart')),
            'work_title'                =>  trim($request->input('work_title')),
            'position_desc'             =>  trim($request->input('position_desc')),
            'chef'                      =>  trim($request->input('chef')),
        );

        $messages   =   array(
            "lname.required"             =>  "Поле обязательно для заполнения",
            "lname.max"                  =>  "Поле не должно быть длиннее, чем 255 символов",
            "fname.required"             =>  "Поле обязательно для заполнения",
            "fname.max"                  =>  "Поле не должно быть длиннее, чем 255 символов",
            "mname.max"                  =>  "Поле не должно быть длиннее, чем 255 символов",
            "phone.max"                  =>  "Поле не должно быть длиннее, чем 3 цифры - это местный номер",
            "room.max"                   =>  "Поле не должно быть длиннее, чем 4 символа",
            "city_phone.max"             =>  "Поле не должно быть длиннее, чем 18 символов",
            "mobile_phone.max"           =>  "Поле не должно быть длиннее, чем 18 символов",
            "email.email"                =>  "Поле должно быть формата email",
            "email_secondary.email"      =>  "Поле должно быть формата email",
            "position_desc.max"          =>  "Поле не должно быть длиннее, чем 255 символов",
            "numpark.integer"            =>  "Поле должно быть номером места, числом",
            "role_id.required"           =>  "Поля обязательно для заполнения",
            "role_id.integer"            =>  "Поле должно быть числом",
        );
        $validator = Validator::make($request->all(), [
            'numpark'           =>  'nullable|integer',
            'role_id'           =>  'required|integer',
            'position_desc'     =>  'nullable|string|max:255',
            'lname'             =>  'string|max:255|required',
            'fname'             =>  'string|max:255|required',
            'mname'             =>  'nullable|string|max:255',
            'phone'             =>  'nullable|string|max:3',
            'room'              =>  'nullable|string|max:4',
            'city_phone'        =>  'nullable|string|max:15',
            'mobile_phone'      =>  'nullable|string|max:18',
            'email'             =>  'nullable|string|email',
            'email_secondary'   =>  'nullable|string|email',
        ],  $messages);
        if ($validator->fails()) {
            return redirect()->route('moderate.users.edit', ["id"   =>  $id])
                ->withErrors($validator)
                ->withInput();
        }

        $ps =   Profiles_Saved::where("user_id",    "=",    $id)->first();
        if($ps) {
            Profiles_Saved_Data::where("ps_id", '=',    $ps->id)->delete();
            $ps->delete();
        }
        $ps =   new Profiles_Saved();

        $ps->user_id        =   $id;
        $ps->creator_id     =   Auth::user()->id;
        $ps->notified       =   1;
        $ps->save();

        foreach($updates_fields as $key =>  $value) {
            if($key ==  "birthday") {
                $birthday_parts =   explode(".",    $value);
                if(count($birthday_parts)   ==  3) {
                    $value   =   date("Y-m-d", strtotime($value));
                }
                else {
                    $value  =   null;
                }
            }
            if($key ==  "workstart") {
                $workstart_parts =   explode(".",    $value);
                if(count($workstart_parts)   ==  3) {
                    $value   =   date("Y-m-d", strtotime($value));
                }
                else {
                    $value  =   null;
                }
            }
            if($key ==  "chef") {
                if($value) {
                    /*$max = 2;
                    $deps = Dep::selectRaw("MAX(LENGTH(parent_id)) as max")->first();
                    $max = $deps["max"];*/
                    $curDep = $user->dep_id;
                    $userDep = Dep::findOrFail($curDep);
                    $value = mb_strlen($userDep["parent_id"], "UTF-8");
                    //$value = $max - $cur_length;
                }
                else {
                    $value  =   null;
                }
            }
            if($value   != $user->$key) {
                $psd =   new Profiles_Saved_Data();

                $psd->ps_id         =   $ps->id;
                $psd->creator_id    =   Auth::user()->id;


                $psd->field_name    =   $key;
                $psd->old_value     =   $user->$key;
                $psd->new_value     =   $value;
                $psd->status        =   2;
                $psd->save();
            }
        }

        $user   =   User::findOrFail($id);
        $psd    =   Profiles_Saved_Data::where("ps_id", "=",    $ps->id)->get();
        foreach($psd as $item) {
            if ($item->field_name != "work_title"   &&  $item->field_name   !=  "chef") {
                $user->{$item->field_name} = $item->new_value;
            }
            else {
                if($item->field_name    ==  "work_title") {
                    $wt_record =   Deps_Peoples::where("people_id", "=", $user->id)->first();
                    if(!$wt_record) {
                        $wt_record = new Deps_Peoples();
                        $wt_record->chef    =   null;
                        $wt_record->people_id  =   $user->id;
                    }
                    $wt_record->work_title  =   $item->new_value;
                    $wt_record->save();
                }
                if($item->field_name    ==  "chef") {
                    $wt_record =   Deps_Peoples::where("people_id", "=", $user->id)->first();
                    if(!$wt_record) {
                        $wt_record = new Deps_Peoples();
                        $wt_record->chef    =   null;
                        $wt_record->people_id  =   $user->id;
                    }
                    $wt_record->chef  =   $item->new_value;
                    $wt_record->save();
                }
            }
            $item->delete();
        }

        $user->numupdates   =   $user->numupdates   +   1;
        $user->save();
        $ps->delete();

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