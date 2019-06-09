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

Route::group(['middleware' => 'blog.navbars'], function() {
	Route::get('/', 'BlogController@index');
	Route::get('tag/{slug}', 'BlogController@index')->where('slug','[0-9A-Za-z-/]+');
	Route::get('category/{slug}', 'BlogController@index')->where('slug','[0-9A-Za-z-/]+');
	Route::get('{slug}', 'BlogController@index')->where('slug','[0-9A-Za-z-/]+');
});
