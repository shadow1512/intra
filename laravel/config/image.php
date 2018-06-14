<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | Image Driver
    |--------------------------------------------------------------------------
    |
    | Intervention Image supports "GD Library" and "Imagick" to process images
    | internally. You may choose one of them according to your PHP
    | configuration. By default PHP's "GD Library" implementation is used.
    |
    | Supported: "gd", "imagick"
    |
    */

    'driver'            =>  'imagick',
    'default_avatar'    =>  '/images/faces/default.png',
    'default_cover'     =>  '/images/books/book_default.png',
    'avatar_path'       =>  '/users/faces',
    'avatar_width'      =>  150,
    'cover_path'        =>  '/library/covers',
    'book_path'         =>  '/library/books',
    'cover_width'       =>  280,

);
