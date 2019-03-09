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

Route::get('/',         'HomeController@index')->name('home');
Route::get('/parking',  'HomeController@parking')->name('parking');

Route::get('/news', 'NewsController@index')->name('news.list');
Route::get('/news/{id}', 'NewsController@item')->name('news.item');

Route::get('/rooms/book/{id}', 'RoomsController@book')->name('rooms.book');
Route::get('/rooms/book/{id}/{direction}/{num}', 'RoomsController@book')->name('rooms.book.otherweeks');
Route::post('/rooms/book/{id}', 'RoomsController@createbooking')->name('rooms.book.create');

Route::get('/services/teh', 'ServicesController@teh')->name('services.teh');
Route::get('/services/cartridge', 'ServicesController@cartridge')->name('services.cartridge');
Route::get('/services/mail', 'ServicesController@mail')->name('services.mail');

Route::get('/foto', 'GalleryController@index')->name('foto');
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
Route::post('/profile/update', 'ProfileController@update')->name('profile.update');
Route::get('/profile/add/{id}', 'ProfileController@addcontact')->name('profile.addcontact');
Route::get('/profile/deleteavatar', 'ProfileController@deleteavatar')->name('profile.deleteavatar');
Route::post('/profile/updateavatar', 'ProfileController@updateavatar')->name('profile.updateavatar');

Route::get('/kitchen/camera', 'RoomsController@index')->name('kitchen.camera');
Route::get('/kitchen/menu', 'RoomsController@index')->name('kitchen.menu');
Route::get('/kitchen/bills', 'RoomsController@index')->name('kitchen.bills');

Route::get('/staff', 'RoomsController@index')->name('staff');
Route::post('/search', 'SearchController@index')->name('search');
Route::post('/search/directory', 'SearchController@directory')->name('search.directory');

Route::post('/auth/login', 'AdLoginController@login')->name('auth.login');
Route::post('/auth/logout', 'AdLoginController@logout')->name('auth.logout');

Route::get('/indexer', 'IndexerController@index')->name('indexer.index');
Route::get('/indexer/dir', 'IndexerController@dirloader')->name('indexer.dir');
Route::get('/indexer/struct', 'IndexerController@sructloader')->name('indexer.struct');
Route::get('/indexer/xml', 'IndexerController@createXMLFromUpdatedUsers')->name('indexer.xml');



//Auth::routes();


Route::group(['prefix' => 'moderate'], function () {
    Route::get('/', 'ModerateController@index')->name('moderate');
    Route::get('/news/create', 'ModerateController@newscreate')->name('moderate.news.create');
    Route::get('/news/edit/{id}', 'ModerateController@newsedit')->name('moderate.news.edit');
    Route::put('/news/update/{id}', 'ModerateController@newsupdate')->name('moderate.news.update');
    Route::put('/news/store', 'ModerateController@newsstore')->name('moderate.news.store');
    Route::delete('/news/delete/{id}', 'ModerateController@newsdelete')->name('moderate.news.delete');
    Route::get('/rooms', 'ModerateController@rooms')->name('moderate.rooms.index');
    Route::get('/rooms/create', 'ModerateController@roomscreate')->name('moderate.rooms.create');
    Route::get('/rooms/edit/{id}', 'ModerateController@roomsedit')->name('moderate.rooms.edit');
    Route::put('/rooms/update/{id}', 'ModerateController@roomsupdate')->name('moderate.rooms.update');
    Route::put('/rooms/store', 'ModerateController@roomsstore')->name('moderate.rooms.store');
    Route::delete('/rooms/delete/{id}', 'ModerateController@roomsdelete')->name('moderate.rooms.delete');
    Route::get('/library', 'ModerateController@library')->name('moderate.library.index');
    Route::get('/library/razdel/create', 'ModerateController@librarycreate')->name('moderate.library.create');
    Route::get('/library/razdel/edit/{id}', 'ModerateController@libraryedit')->name('moderate.library.edit');
    Route::put('/library/razdel/update/{id}', 'ModerateController@libraryupdate')->name('moderate.library.update');
    Route::put('/library/razdel/store', 'ModerateController@librarystore')->name('moderate.library.store');
    Route::delete('/library/razdel/delete/{id}', 'ModerateController@librarydelete')->name('moderate.library.delete');
    Route::get('/library/book/create', 'ModerateController@librarycreatebook')->name('moderate.library.createbook');
    Route::get('/library/book/edit/{id}', 'ModerateController@libraryeditbook')->name('moderate.library.editbook');
    Route::put('/library/book/update/{id}', 'ModerateController@libraryupdatebook')->name('moderate.library.updatebook');
    Route::put('/library/book/store', 'ModerateController@librarystorebook')->name('moderate.library.storebook');
    Route::delete('/library/book/delete/{id}', 'ModerateController@librarydeletebook')->name('moderate.library.deletebook');
    Route::put('/library/book/update/cover/{id}', 'ModerateController@libraryupdatebookcover')->name('moderate.library.updatebookcover');
    Route::get('/library/book/delete/cover/{id}', 'ModerateController@librarydeletebookcover')->name('moderate.library.deletebookcover');
    Route::put('/library/book/update/file/{id}', 'ModerateController@libraryupdatebookfile')->name('moderate.library.updatebookfile');
    Route::get('/library/book/delete/file/{id}', 'ModerateController@librarydeletebookfile')->name('moderate.library.deletebookfile');

    Route::get('/users', 'ModerateController@users')->name('moderate.users.start');
    Route::get('/users/{letter}', 'ModerateController@users')->name('moderate.users.index');
    Route::get('/users/edit/{id}', 'ModerateController@usersedit')->name('moderate.users.edit');
    Route::put('/users/update/{id}', 'ModerateController@usersupdate')->name('moderate.users.update');
    Route::put('/users/update/avatar/{id}', 'ModerateController@usersupdateavatar')->name('moderate.users.updateavatar');
    Route::get('/users/delete/avatar/{id}', 'ModerateController@usersdeleteavatar')->name('moderate.users.deleteavatar');
    Route::get('/foto', 'ModerateController@foto')->name('moderate.foto.index');
    Route::get('/foto/create', 'ModerateController@fotocreate')->name('moderate.foto.create');
    Route::get('/foto/edit/{id}', 'ModerateController@fotoedit')->name('moderate.foto.edit');
    Route::put('/foto/update/{id}', 'ModerateController@fotoupdate')->name('moderate.foto.update');
    Route::put('/foto/store', 'ModerateController@fotostore')->name('moderate.foto.store');
    Route::put('/foto/update/image/{id}', 'ModerateController@fotoupdateimage')->name('moderate.foto.updateimage');
    Route::delete('/foto/delete/image/{id}', 'ModerateController@fotodeleteimage')->name('moderate.foto.deleteimage');
});
