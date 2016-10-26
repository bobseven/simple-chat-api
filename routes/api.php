
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




Route::group([
    'prefix' => 'restricted',
    'middleware' => 'auth:api',
], function () {

    // Authentication Routes...
    Route::get('logout', 'Auth\LoginController@logout');

    Route::get('/test', function () {
        return 'authenticated';
    });
});

Route::post('login', 'Auth\LoginController@login');


Route::group(['prefix' => 'user'], function () {
    Route::get("", 'UserController@show');
    Route::post("", 'UserController@register');
    Route::post("login", 'UserController@login');
});

Route::group(['prefix' => 'message'], function () {
    Route::get("{id}", 'MessageController@show');
    Route::post("", 'MessageController@send');
});

Route::group(['prefix' => 'channel'], function () {
    Route::get("{id}", 'ChannelController@show');
    Route::post("message", 'ChannelController@send');
    Route::post("new/{userId}", 'ChannelController@create');
});

Route::group(['prefix' => 'channels'], function () {
    Route::get("list", "ChannelController@showAll");
});

Route::group(['prefix' => 'search'], function () {
    Route::post("contacts", "SearchController@search");
});
