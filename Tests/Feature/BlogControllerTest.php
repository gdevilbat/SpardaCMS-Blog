<?php

namespace Gdevilbat\SpardaCMS\Modules\Blog\Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BlogControllerTest extends TestCase
{
	use RefreshDatabase, \Gdevilbat\SpardaCMS\Modules\Core\Tests\ManualRegisterProvider;

    /**
     * Homepage Test.
     *
     * @return void
     */
    public function testHomepage()
    {
    	$response = $this->get('/');

        $response->assertSuccessful();
    }

    public function testPage()
    {
    	$post = \Gdevilbat\SpardaCMS\Modules\Post\Entities\Post::where(['post_type' => 'page'])->first();
    	$post->post_status = 'publish';
    	$post->save();

    	$response = $this->get(url($post->post_slug));

        $response->assertSuccessful();
    }

    public function testBlog()
    {
    	$post = \Gdevilbat\SpardaCMS\Modules\Post\Entities\Post::where(['post_type' => 'post'])->first();
    	$post->post_status = 'publish';
    	$post->save();

    	$url = url($post->created_at->format('Y').'/'.$post->created_at->format('m').'/'.$post->post_slug.'.html');

    	$response = $this->get($url);

        $response->assertSuccessful();
    }

    /*public function testTaxonomy()
    {
    	$post = \Gdevilbat\SpardaCMS\Modules\Post\Entities\Post::where(['post_type' => 'post'])->first();
    	$post->post_status = 'publish';
    	$post->save();

    	$response = $this->get(url('category/uncategorized/'));

        $response->assertSuccessful();
    }*/
}
