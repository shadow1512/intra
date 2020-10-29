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
    'avatar_width'      =>  400,
    'avatar_height'     =>  700,
    'cover_path'        =>  '/library/covers',
    'book_path'         =>  '/library/books',
    'gallery_path'      =>  '/gallery',
    'directory_path'    =>  storage_path('app/public/directory'),
    'csv_directory_path'=>  storage_path('app/public/directory/csv'),
    'photo_thumb_width' => 150,
    'cover_width'       =>  200,
    'cover_height'      =>  280,
    'gallery_photo_thumb_width'     =>  150,
    'gallery_photo_thumb_height'    =>  150,
    'image_types'       =>  array('image/jpeg', 'image/pjpeg', 'image/png'),
    'video_types'       =>  array('video/x-flv', 'video/mp4', 'application/x-mpegURL', 'video/MP2T', 'video/3gpp', 'video/quicktime', 'video/x-msvideo', 'video/x-ms-wmv'),
);
