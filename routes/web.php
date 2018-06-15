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
//工具（图片上传）
Route::group(['namespace'=> 'Util'], function () {
    Route::get('images/{id}', 'ImagesController@show');
    Route::get('qrcode/{id}', 'ImagesController@qrcode');
    Route::post('images/{id}', 'ImagesController@store');

    Route::get('files/{id}', 'FileController@show');

});

Route::group(['namespace'=>"Backend\Admin"], function () {

    Route::get('login','AuthController@getLogin');
    Route::post('login','AuthController@postLogin');

});
Route::group(['namespace'=>"Backend\Admin",'middleware'=>['web', 'guest']], function () {
    Route::get('logout','AuthController@getLogout');
    Route::get('setting', 'AuthController@getSetting');
    Route::put('setting', 'AuthController@putSetting');
    Route::resource('users','UserController');
    Route::resource('menus','MenuController');
    Route::resource('roles','RoleController');
    Route::resource('permissions','PermissionController');
    Route::resource('logs','LogController');

});

Route::group(['namespace'=>"Backend\Index",'middleware'=>['web', 'guest']], function () {
    Route::get('/','HomeController@Index');
    Route::get('system','SystemController@index');
    Route::get('system/edit','SystemController@edit');
    Route::post('system/store','SystemController@store');
    Route::resource('index','IndexController');
    Route::get('read/{id}','IndexController@read');
    Route::get('count/{id}','IndexController@count');
});
