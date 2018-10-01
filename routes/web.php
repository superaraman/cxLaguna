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


Auth::routes();

//Route::get('/services', 'PagesController@services');
//Route::get('/settings', 'PagesController@settings');
//Route::post('/settings/updateProfile', 'PagesController@updateProfile');

Route::get('/home', 'PagesController@index')->name('home');
Route::get('/', function () {
    return redirect()->route('home');
});

Route::get('/logout', 'Auth\LoginController@logout')->name('logout');

Route::get('/manage/users', 'AdminController@index')->name('admin');
Route::get('/getListOfAdmins', 'AdminController@getListofAdmins');
Route::get('/getCurrentAdmin', 'AdminController@getCurrentAdmin');
Route::post('/addAdmin', 'AdminController@addAdmin');
Route::post('/deleteAdmin', 'AdminController@deleteAdmin');
Route::post('/updateAdminRole', 'AdminController@updateAdminRole');