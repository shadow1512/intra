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
Route::get('/rooms/book/view/{id}', 'RoomsController@viewbooking')->name('rooms.book.view');
Route::post('/rooms/book/save/{id}', 'RoomsController@savebooking')->name('rooms.book.save');
Route::get('/rooms/book/delete/{id}', 'RoomsController@deletebooking')->name('rooms.book.delete');

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
Route::get('/profile/delete/{id}', 'ProfileController@deletecontact')->name('profile.deletecontact');
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
Route::get('/indexer/struct', 'IndexerController@structloader')->name('indexer.struct');
Route::get('/indexer/xml', 'IndexerController@createXMLFromUpdatedUsers')->name('indexer.xml');



//Auth::routes();


Route::group(['prefix' => 'moderate',   'middleware'    =>  ['moderate']], function () {
    Route::get('/', 'ModerateController@index')->name('moderate');

    Route::group(['prefix'  =>  'news', 'middleware'    =>  ['content']],   function() {
        Route::get('/', 'ModerateController@newslist')->name('moderate.news.list');
        Route::get('/create', 'ModerateController@newscreate')->name('moderate.news.create');
        Route::get('/edit/{id}', 'ModerateController@newsedit')->name('moderate.news.edit');
        Route::put('/update/{id}', 'ModerateController@newsupdate')->name('moderate.news.update');
        Route::put('/store', 'ModerateController@newsstore')->name('moderate.news.store');
        Route::delete('/delete/{id}', 'ModerateController@newsdelete')->name('moderate.news.delete');
    });

    Route::group(['prefix'  =>  'library', 'middleware'    =>  ['content']],   function() {
        Route::get('/', 'ModerateController@library')->name('moderate.library.index');
        Route::get('/razdel/create', 'ModerateController@librarycreate')->name('moderate.library.create');
        Route::get('/razdel/edit/{id}', 'ModerateController@libraryedit')->name('moderate.library.edit');
        Route::put('/razdel/update/{id}', 'ModerateController@libraryupdate')->name('moderate.library.update');
        Route::put('/razdel/store', 'ModerateController@librarystore')->name('moderate.library.store');
        Route::delete('/razdel/delete/{id}', 'ModerateController@librarydelete')->name('moderate.library.delete');
        Route::get('/book/create', 'ModerateController@librarycreatebook')->name('moderate.library.createbook');
        Route::get('/book/edit/{id}', 'ModerateController@libraryeditbook')->name('moderate.library.editbook');
        Route::put('/book/update/{id}', 'ModerateController@libraryupdatebook')->name('moderate.library.updatebook');
        Route::put('/book/store', 'ModerateController@librarystorebook')->name('moderate.library.storebook');
        Route::delete('/book/delete/{id}', 'ModerateController@librarydeletebook')->name('moderate.library.deletebook');
        Route::put('/book/update/cover/{id}', 'ModerateController@libraryupdatebookcover')->name('moderate.library.updatebookcover');
        Route::get('/book/delete/cover/{id}', 'ModerateController@librarydeletebookcover')->name('moderate.library.deletebookcover');
        Route::put('/book/update/file/{id}', 'ModerateController@libraryupdatebookfile')->name('moderate.library.updatebookfile');
        Route::get('/book/delete/file/{id}', 'ModerateController@librarydeletebookfile')->name('moderate.library.deletebookfile');
    });

    Route::group(['prefix'  =>  'foto', 'middleware'    =>  ['content']],   function() {
        Route::get('/', 'ModerateController@foto')->name('moderate.foto.index');
        Route::get('/create', 'ModerateController@fotocreate')->name('moderate.foto.create');
        Route::get('/edit/{id}', 'ModerateController@fotoedit')->name('moderate.foto.edit');
        Route::put('/update/{id}', 'ModerateController@fotoupdate')->name('moderate.foto.update');
        Route::put('/store', 'ModerateController@fotostore')->name('moderate.foto.store');
        Route::delete('/delete/{id}', 'ModerateController@fotodelete')->name('moderate.foto.delete');
        Route::put('/update/image/{id}', 'ModerateController@fotoupdateimage')->name('moderate.foto.updateimage');
        Route::delete('/delete/image/{id}', 'ModerateController@fotodeleteimage')->name('moderate.foto.deleteimage');
    });

    Route::group(['prefix'  =>  'rooms', 'middleware'    =>  ['rooms']],   function() {
        Route::get('/', 'ModerateController@rooms')->name('moderate.rooms.index');
        Route::get('/create', 'ModerateController@roomscreate')->name('moderate.rooms.create');
        Route::get('/edit/{id}', 'ModerateController@roomsedit')->name('moderate.rooms.edit');
        Route::put('/update/{id}', 'ModerateController@roomsupdate')->name('moderate.rooms.update');
        Route::put('/store', 'ModerateController@roomsstore')->name('moderate.rooms.store');
        Route::delete('/delete/{id}', 'ModerateController@roomsdelete')->name('moderate.rooms.delete');
    });

    Route::group(['prefix'  =>  'users', 'middleware'    =>  ['support']],   function() {
        Route::get('/', 'ModerateController@users')->name('moderate.users.start');
        Route::get('/{letter}', 'ModerateController@users')->name('moderate.users.index');
        Route::get('/edit/{id}', 'ModerateController@usersedit')->name('moderate.users.edit');
        Route::put('/update/{id}', 'ModerateController@usersupdate')->name('moderate.users.update');
        Route::put('/update/avatar/{id}', 'ModerateController@usersupdateavatar')->name('moderate.users.updateavatar');
        Route::get('/delete/avatar/{id}', 'ModerateController@usersdeleteavatar')->name('moderate.users.deleteavatar');
    });

    Route::group(['prefix'  =>  'dinner', 'middleware'    =>  ['aho']],   function() {
        Route::get('/', 'ModerateController@dinnerlist')->name('moderate.dinner.list');
        Route::get('/create', 'ModerateController@dinnercreate')->name('moderate.dinner.create');
        Route::get('/edit/{id}', 'ModerateController@dinneredit')->name('moderate.dinner.edit');
        Route::put('/update/{id}', 'ModerateController@dinnerupdate')->name('moderate.dinner.update');
        Route::put('/store', 'ModerateController@dinnerstore')->name('moderate.dinner.store');
        Route::delete('/delete/{id}', 'ModerateController@dinnerdelete')->name('moderate.dinner.delete');
    });

    Route::group(['prefix'  =>  'admins', 'middleware'    =>  ['admin']],   function() {
        Route::get('/', 'ModerateController@adminslist')->name('moderate.admins.list');
        Route::get('/create', 'ModerateController@adminscreate')->name('moderate.admins.create');
        Route::get('/edit/{id}', 'ModerateController@adminsedit')->name('moderate.admins.edit');
        Route::put('/update/{id}', 'ModerateController@adminsupdate')->name('moderate.admins.update');
        Route::put('/store', 'ModerateController@adminsstore')->name('moderate.admins.store');
        Route::delete('/delete/{id}', 'ModerateController@adminsdelete')->name('moderate.admins.delete');
    });
});
