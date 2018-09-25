<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Rooms;
use App\User;
use Auth;

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

            $view->with(["rooms" =>  $rooms, "contacts"  =>  $contacts]);
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
