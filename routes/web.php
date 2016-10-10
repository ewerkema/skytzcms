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

    Route::get('pages/{page}/content', array('as' => 'pages.content', 'uses' => 'PageController@content'));
    Route::patch('pages/{page}/content', array('as' => 'pages.savecontent', 'uses' => 'PageController@saveContent'));
    Route::patch('pages/{page}/blocks', array('as' => 'pages.updateblocks', 'uses' => 'PageController@updateBlocks'));
    Route::resource('pages', 'PageController');

    Route::get('/{slug}', array('as' => 'page.show', 'uses' => 'CmsTemplateController@show'));
});

Route::get('/', array('as' => 'page.show', 'uses' => 'TemplateController@show'));
Route::get('/{slug}', array('as' => 'page.show', 'uses' => 'TemplateController@show'));
