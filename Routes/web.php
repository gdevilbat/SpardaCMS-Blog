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
	Route::get('{year}/{month}/{slug}.html', 'BlogController@blog');

	/*================================
	=            Taxonomy            =
	================================*/
	
		$taxonomy = \Gdevilbat\SpardaCMS\Modules\Taxonomy\Entities\TermTaxonomy::groupBy('taxonomy')->pluck('taxonomy');

		foreach ($taxonomy as $key => $value) 
		{
			Route::get($value.'/{slug}', 'BlogController@taxonomyPost')->where('slug','[0-9A-Za-z-/]+');
		}
	
	/*=====  End of Taxonomy  ======*/
	

	Route::get('{slug}', 'BlogController@page')->where('slug','[0-9A-Za-z-/]+');
});
