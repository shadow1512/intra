<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'NewsController@index')->name('home');
Route::get('/news', 'NewsController@index')->name('news.list');
Route::get('/news/{id}', 'NewsController@item')->name('news.item');
//Auth::routes();


Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});
