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
    'avatar_width'      =>  600,
    'avatar_height'     =>  600,
    'cover_path'        =>  '/library/covers',
    'book_path'         =>  '/library/books',
    'gallery_path'      =>  '/gallery',
    'directory_path'    =>  storage_path('app/public/directory'),
    'photo_thumb_width' => 150,
    'cover_width'       =>  200,
    'cover_height'      =>  280,
    'gallery_photo_thumb_width'     =>  150,
    'gallery_photo_thumb_height'    =>  150,
);
