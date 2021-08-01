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

Route::group(['middleware' => ['appearance.navbars', 'core.maintenance_mode']], function() {
	Route::get('/', 'BlogController@index')->name('blog.homepage');
	Route::get('sitemap/blog.xml', 'SitemapController@index');
	Route::get('{year}/{month}/{slug}.html', 'BlogController@blog');

	/*================================
	=            Taxonomy            =
	================================*/

		if(!App::environment('testing'))
		{
			$taxonomy = \Gdevilbat\SpardaCMS\Modules\Taxonomy\Entities\TermTaxonomy::groupBy('taxonomy')->pluck('taxonomy');

			foreach ($taxonomy as $key => $value) 
			{
				Route::get($value.'/{slug}', 'BlogController@taxonomyPost')->where('slug','[0-9A-Za-z-/]+');
			}
		}
		else
		{
			Route::get('category/{slug}', 'BlogController@taxonomyPost')->where('slug','[0-9A-Za-z-/]+');
		}
	
	
	/*=====  End of Taxonomy  ======*/
	

	Route::get('search', 'BlogController@search');
	Route::get('{slug}', 'BlogController@page');
});
