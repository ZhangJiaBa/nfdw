<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group(['namespace' => 'Api\Users'], function () {
    Route::post('get_user_info', 'UsersController@getUserInfo');
});
Route::group(['namespace' => 'Api\Users', 'middleware' => [ 'jwt.auth']], function () {
    Route::post('update_user_info', 'UsersController@updateUserInfo')->middleware('jwt.auth');
});