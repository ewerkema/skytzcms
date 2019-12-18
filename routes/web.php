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

    Route::get('pages/{page}/grid', array('as' => 'pages.grid', 'uses' => 'PageController@grid'));
    Route::get('pages/{page}/content', array('as' => 'pages.content', 'uses' => 'PageController@content'));
    Route::patch('pages/{page}/grid', array('as' => 'pages.updategrid', 'uses' => 'PageController@updateGrid'));
    Route::patch('pages/{page}/content', array('as' => 'pages.updatecontent', 'uses' => 'PageController@updateContent'));
    Route::post('pages/publish', array('as' => 'pages.publish', 'uses' => 'PageController@publish'));
    Route::post('pages/{page}/publish', array('as' => 'pages.publishPage', 'uses' => 'PageController@publishPage'));
    Route::resource('pages', 'PageController');

    Route::patch('users/current', 'UserController@updateCurrentUser');
    Route::resource('users', 'UserController');
    Route::patch('settings', 'SettingController@update');
    Route::resource('media', 'MediaController');
    Route::delete('media', 'MediaController@destroyMany');
    Route::post('media/{medium}/header', 'MediaController@createHeader');
    Route::resource('articleGroups', 'ArticleGroupController');
    Route::resource('articles', 'ArticleController');
    Route::resource('albums', 'AlbumController');
    Route::patch('albums/{album}/order', array('as' => 'albums.updateorder', 'uses' => 'AlbumController@updateOrder'));
    Route::resource('albums.media', 'AlbumMediaController');
    Route::resource('sliders', 'SliderController');
    Route::resource('sliders.media', 'SliderMediaController');
    Route::resource('forms', 'FormController');
    Route::resource('formFields', 'FormFieldController');
    Route::resource('modules', 'ModuleController');
    Route::resource('htmlBlocks', 'HtmlBlockController');
    Route::resource('projects', 'ProjectController');
    Route::resource('projectGroups', 'ProjectGroupController');
    Route::resource('socials', 'SocialController');
    Route::resource('headers', 'HeaderController');
    Route::resource('folders', 'FolderController');
    Route::resource('folders.media', 'FolderMediaController');
    Route::patch('menu/order', array('as' => 'menu.updateorder', 'uses' => 'MenuController@updateOrder'));
    Route::resource('menu', 'MenuController');
//
    Route::get('/{slug}', array('as' => 'page.show', 'uses' => 'TemplateController@show'));
    Route::get('/{slug}/{childSlug}', array('as' => 'page.show', 'uses' => 'TemplateController@show'));
});

Route::get('/', array('as' => 'page.show', 'uses' => 'TemplateController@show'));
Route::get('/{slug}', array('as' => 'page.show', 'uses' => 'TemplateController@show'));
Route::get('/{slug}/{childSlug}', array('as' => 'page.show', 'uses' => 'TemplateController@show'));
Route::post('forms/{form}/send', array('as' => 'forms.send', 'uses' => 'FormController@send'));
