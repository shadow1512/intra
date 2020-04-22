<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use DB;
use App\Rooms;
use App\User;
use App\Menu_Config;
use Auth;
use Cookie;
use Illuminate\Cookie\CookieJar;
use Illuminate\Support\Facades\Cache;

use View;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Если композер реализуется в функции-замыкании:
        View::composer('nav', function($view)
        {
            $rooms  =   Rooms::orderBy('name')->get();
            $contacts   =   array();
            //Контакты выбранные
            if(Auth::check()) {
                $contacts = User::select("users.id", "users.avatar", "users.fname", "users.lname", "users.mname", "users.position", "users.email", "users.phone")
                    ->leftJoin('user_contacts', 'user_contacts.contact_id', '=', 'users.id')->where('user_contacts.user_id', '=', Auth::user()->id)->get();
            }

            //$menu
            $hide_menues    =   array();
            $menu_items =   Cache::remember('menu_items', 60, function () {
                return Menu_Config::getLevel(null, array());
            });

            foreach($menu_items["root"] as $root_item) {
                $hide_menues[$root_item->id]  =   Cookie::get('hide_menu_'    .   ($root_item->id));
            }

            $view->with(["rooms" =>  $rooms, "contacts"  =>  $contacts, "menu_items" => $menu_items,    "hide_menues"   =>  $hide_menues]);
        });

        View::composer('dinner',    function($view)
        {
            //счет в столовой
            $summ   =   0;
            $bills  =   array();
            $bill=  null;
            if (Auth::check()) {
                $bill = DB::table('users_dinner_bills')->where('user_id', Auth::user()->id)->orderBy('date_created', 'desc')->first();
                if($bill) {
                    $summ   =   $bill->summ;
                }
                $bills =   DB::table('users_dinner_bills')->selectRaw('MONTH(date_created) as mdc, MAX(summ) as ms')
                    ->where("user_id", "=",   Auth::user()->id)
                    ->whereRaw("DAY(date_created)=16")
                    ->groupBy('mdc')->orderBy('mdc',    'desc')->limit(8)->get();
            }

            //Меню
            $ch = curl_init('http://oldintra.kodeks.ru/cooking/menu1.html');
            curl_setopt($ch, CURLINFO_HEADER_OUT, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $res = curl_exec($ch);
            $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $kitchen_menu   =   "";
            $res=   iconv("windows-1251",    "utf-8",   $res);
            if($status_code == 200) {
                preg_match("/<body[^>]*>(.*?)<\/body>/ius", $res, $matches);
                if(count($matches)) {
                    $kitchen_menu   =   $matches[1];
                }
            }



            $view->with([   'kitchen_menu'  =>  $kitchen_menu,
                            'summ'          =>  $summ,
                            'curbill'       =>  $bill,
                            'bills'         =>  $bills]);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
