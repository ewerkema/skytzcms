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

Route::group(['prefix' => 'cms'], function() {
    Auth::routes();

    Route::get('/', function () {
        return View::make('auth.login');
    })->middleware('guest');

    Route::get('/{slug}', array('as' => 'page.show', 'uses' => 'CmsPageController@show'));
});

Route::get('/', array('as' => 'page.show', 'uses' => 'PageController@show'));
Route::get('/{slug}', array('as' => 'page.show', 'uses' => 'PageController@show'));
