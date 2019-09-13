<?php

namespace Gdevilbat\SpardaCMS\Modules\Blog\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use \Gdevilbat\SpardaCMS\Modules\Post\Entities\Post;

class SitemapController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $data['posts'] = Post::where(['post_type' => 'post', 'post_status' => 'publish'])
                                    ->get();

        $data['pages'] = Post::where(['post_type' => 'page', 'post_status' => 'publish'])
                                ->where('post_slug', '!=', 'homepage')
                                ->get();

        return response()->view('blog::sitemap', $data)
                        ->header('Content-Type', 'text/xml');
    }
}
