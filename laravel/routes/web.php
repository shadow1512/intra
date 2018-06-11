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

Route::get('/', 'HomeController@index')->name('home');

Route::get('/news', 'NewsController@index')->name('news.list');
Route::get('/news/{id}', 'NewsController@item')->name('news.item');

Route::get('/rooms/book/{id}', 'RoomsController@book')->name('rooms.book');

Route::get('/services/teh', 'RoomsController@index')->name('services.teh');
Route::get('/services/cartridge', 'RoomsController@index')->name('services.cartridge');
Route::get('/services/mail', 'RoomsController@index')->name('services.mail');

Route::get('/holidays', 'RoomsController@index')->name('holidays');
Route::get('/library', 'LibraryController@index')->name('library');
Route::get('/library/razdel/{id}', 'LibraryController@index')->name('library.razdel');

Route::get('/people', 'UserController@index')->name('people');
Route::get('/people/search', 'UserController@search')->name('people.search');
Route::get('/people/dept/{id}', 'UserController@search')->name('people.dept');
Route::get('/people/dept', 'UserController@search')->name('people.root');
Route::get('/people/unit/{id}', 'UserController@unit')->name('people.unit');
Route::get('/people/birthday', 'UserController@index')->name('people.birthday');
Route::get('/people/new', 'UserController@index')->name('people.new');

Route::get('/profile', 'ProfileController@index')->name('profile');

Route::get('/kitchen/camera', 'RoomsController@index')->name('kitchen.camera');
Route::get('/kitchen/menu', 'RoomsController@index')->name('kitchen.menu');
Route::get('/kitchen/bills', 'RoomsController@index')->name('kitchen.bills');

Route::get('/staff', 'RoomsController@index')->name('staff');
Route::get('/search', 'SearchController@index')->name('search');

Route::post('/auth/login', 'AdLoginController@login')->name('auth.login');
Route::post('/auth/logout', 'AdLoginController@logout')->name('auth.logout');

//Auth::routes();


Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});
