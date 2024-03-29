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
                $contacts = User::select("users.id", "users.avatar", "users.fname", "users.lname", "users.mname", "users.position", "users.email", "users.phone", "users.ip_phone")
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
        
        View::composer('footer',    function($view) {
            //$version    =   passthru('git log --pretty=format:"%h" --max-count=1');
            $version="";
            $view->with([   'version'  =>  $version]);
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
