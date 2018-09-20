<?php

namespace App\Http\Middleware;

use Illuminate\Cookie\Middleware\EncryptCookies as BaseEncrypter;

class EncryptCookies extends BaseEncrypter
{
    /**
     * The names of the cookies that should not be encrypted.
     *
     * @var array
     */
    protected $except = [
        //
        'hide_directory_search',
        'hide_dinner',
        'hide_menu_0',
        'hide_menu_1',
        'hide_menu_2',
        'hide_menu_3',
        'hide_menu_4',
        'hide_menu_5',
        'hide_menu_6'
    ];
}
