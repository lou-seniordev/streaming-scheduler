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

Route::get('/', function () {
    return view('pages.home');
})->middleware(['auth']);

Auth::routes();

Route::get('/home', 'HomeController@index')->name('pages.home');

Route::get('/project', 'ProjectController@index');
Route::get('/project/create', 'ProjectController@create');
Route::get('/project/edit/{id}', 'ProjectController@edit');

Route::get('/playlist', 'PlaylistController@index');
Route::get('/playlist/create', 'PlaylistController@create');
Route::get('/playlist/edit', 'PlaylistController@edit');

Route::get('/videoclip', 'VideoclipController@index');
Route::get('/videoclip/create', 'VideoclipController@create');
Route::get('/videoclip/edit/{id}', 'VideoclipController@edit');
