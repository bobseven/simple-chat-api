<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

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