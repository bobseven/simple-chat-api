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