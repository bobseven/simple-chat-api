
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




Route::post('login', 'Auth\LoginController@login');
Route::post("user/register", 'UserController@register');

Route::group(['prefix' => 'user', 'middleware' => 'auth:api'], function () {
    Route::get("", 'UserController@show');
});

Route::group(['prefix' => 'message', 'middleware' => 'auth:api'], function () {
    Route::get("{id}", 'MessageController@show');
    Route::post("", 'MessageController@send');
});

Route::group(['prefix' => 'channel', 'middleware' => 'auth:api'], function () {
    Route::get("{id}", 'ChannelController@show');
    Route::post("message", 'ChannelController@send');
    Route::post("new/{userId}", 'ChannelController@create');
});

Route::group(['prefix' => 'channels', 'middleware' => 'auth:api'], function () {
    Route::get("", "ChannelController@showAll");
});

Route::group(['prefix' => 'search', 'middleware' => 'auth:api'], function () {
    Route::post("contacts", "SearchController@search");
});
