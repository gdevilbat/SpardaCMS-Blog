<?php

namespace Gdevilbat\SpardaCMS\Modules\Blog\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Gdevilbat\SpardaCMS\Modules\Blog\Foundation\AbstractBlog;
use Gdevilbat\SpardaCMS\Modules\Appearance\Http\Controllers\MenuController;

use Auth;

class BlogController extends AbstractBlog
{
    public function __construct(\Gdevilbat\SpardaCMS\Modules\Post\Repositories\PostRepository $post_repository)
    {
        parent::__construct($post_repository);
        $this->post_type = 'post';
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $query = $this->post_m->where(['post_slug' => 'homepage']);

        if(!Auth::check())
        {
            $query = $query->where('post_status',  'publish');
        }

        $this->data['post'] = $query->first();

        if(empty($this->data['post']))
        {
            return $this->throwError(404);
        }

        $path_view = 'appearance::general.'.$this->data['theme_public']->value.'.templates.parent';

        if(file_exists(module_asset_path('appearance:Resources/views/general/'.$this->data['theme_public']->value.'/content/homepage.blade.php')))
        {
            $path_view = 'appearance::general.'.$this->data['theme_public']->value.'.content.homepage';
        }
        elseif(file_exists(module_asset_path('appearance:Resources/views/general/'.$this->data['theme_public']->value.'/content/page.blade.php')))
        {
            $path_view = 'appearance::general.'.$this->data['theme_public']->value.'.content.page';
        }
        else
        {
            $path_view = 'appearance::general.'.$this->data['theme_public']->value.'.content.post';
        }

        return response()
                ->view($path_view, $this->data);
    }

    public function blog($year, $month, $slug)
    {
        $query = $this->post_repository->with('postMeta', 'author')
                                            ->where(['post_slug' => $slug, 'post_type' => 'post'])
                                            ->whereYear('created_at', $year)
                                            ->whereMonth('created_at', $month);
        if(!Auth::check())
        {
            $query = $query->where('post_status',  'publish');
        }

        $this->data['post'] = $query->first();

        if(empty($this->data['post']))
        {
            return $this->throwError(404);
        }

        $this->data['post_categories'] = $this->getPostCategory($this->data['post']);

        $this->data['post_tags'] = $this->getPostTag($this->data['post']);


        /*===================================
        =            Recent Post            =
        ===================================*/

            $query = $this->post_repository->with('postMeta')
                                                ->where(['post_type' => 'post', 'post_status' => 'publish'])
                                                ->where(\Gdevilbat\SpardaCMS\Modules\Post\Entities\Post::getPrimaryKey(), '!=', $this->data['post']->getKey())
                                                ->latest('created_at');

            if(!Auth::check())
            {
                $query = $query->where('post_status',  'publish');
            }
        
            $this->data['recent_posts_query'] = $query;
            $this->data['recent_posts'] = (clone $query)->limit(3)
                                                ->get();
        
        /*=====  End of Recent Post  ======*/
        

        /*===========================================
        =            Related Post            =
        ===========================================*/
        
            $query = $this->buildPostByTaxonomy($this->data['post_categories']->first())
                                                ->with('postMeta')
                                                ->where(\Gdevilbat\SpardaCMS\Modules\Post\Entities\Post::getPrimaryKey(), '!=', $this->data['post']->getKey())
                                                ->inRandomOrder();

            if(!Auth::check())
            {
                $query = $query->where('post_status',  'publish');
            }

            $this->data['related_posts_query'] = $query;                              
            $this->data['related_posts'] = (clone $query)->limit(3)->get();
        
        /*=====  End of Related Post  ======*/


        /*===========================================
        =            Recomended Post            =
        ===========================================*/
        
            $query = $this->post_repository->with('postMeta')
                                                ->where(['post_type' =>  $this->getPostType()])
                                                ->where(\Gdevilbat\SpardaCMS\Modules\Post\Entities\Post::getPrimaryKey(), '!=', $this->data['post']->getKey())
                                                ->inRandomOrder();

            if(!Auth::check())
            {
                $query = $query->where('post_status',  'publish');
            }

            $this->data['recomended_posts_query'] = $query;
            $this->data['recomended_posts'] = (clone $query)->limit(3)->get();
        
        /*=====  End of Recomended Post  ======*/

        return response()
            ->view($this->getPathView(), $this->data);
    }

    public function page($slug)
    {
        if($slug == 'homepage')
            return redirect(url(''));
        
        $query = $this->post_m::with('postMeta')
                                                ->where(['post_slug' => $slug, 'post_type' => 'page']);

        if(!Auth::check())
        {
            $query = $query->where('post_status',  'publish');
        }

        $this->data['post'] = $query->first();

        if(empty($this->data['post']))
        {
            return $this->throwError(404);
        }

        return response()
            ->view($this->getPathView(), $this->data);
    }

    public function getPostData($taxonomy)
    {
        return $this->buildPostByTaxonomy($taxonomy)->latest($this->post_m->getTableName().'.created_at');
    }

    final protected function getCategoryType()
    {
        return 'category';
    }

    final protected function getTagType()
    {
        return 'tag';
    }
}
