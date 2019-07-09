<?php

namespace Gdevilbat\SpardaCMS\Modules\Blog\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Gdevilbat\SpardaCMS\Modules\Blog\Foundation\AbstractBlog;
use Gdevilbat\SpardaCMS\Modules\Appearance\Http\Controllers\MenuController;

class BlogController extends AbstractBlog
{
    public function __construct()
    {
        parent::__construct();
        $this->post_type = 'post';
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $this->data['post'] = $this->post_m->where('post_slug', 'homepage')->first();
        $path_view = 'appearance::general.'.$this->data['theme_public']->value.'.templates.parent';

        if(empty($this->data['post']))
        {
            return response()
                ->view($path_view, $this->data, 404);
        }

        if(file_exists(module_asset_path('appearance:resources/views/general/'.$this->data['theme_public']->value.'/content/homepage.blade.php')))
        {
            $path_view = 'appearance::general.'.$this->data['theme_public']->value.'.content.homepage';
        }
        elseif(file_exists(module_asset_path('appearance:resources/views/general/'.$this->data['theme_public']->value.'/content/page.blade.php')))
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
        $this->data['post'] = $this->post_m::with('postMeta', 'author')
                                            ->where(['post_slug' => $slug, 'post_type' => 'post', 'post_status' => 'publish'])
                                            ->whereYear('created_at', $year)
                                            ->whereMonth('created_at', $month)
                                            ->firstOrFail();

        $this->data['recent_posts'] = $this->post_m->with('postMeta')
                                            ->where(['post_type' => 'post', 'post_status' => 'publish'])
                                            ->where(\Gdevilbat\SpardaCMS\Modules\Post\Entities\Post::getPrimaryKey(), '!=', $this->data['post']->getKey())
                                            ->latest('created_at')
                                            ->limit(5)
                                            ->get();

        $this->data['post_categories'] = $this->data['post']->load(['taxonomies' => function($query){
                                            $query->where('taxonomy', 'category');
                                        }, 'taxonomies.term'])->taxonomies;

        $this->data['post_tags'] = $this->data['post']->load(['taxonomies' => function($query){
                                            $query->where('taxonomy', 'tag');
                                        }, 'taxonomies.term'])->taxonomies;

        $path_view = 'appearance::general.'.$this->data['theme_public']->value.'.templates.parent';

        if(empty($this->data['post']))
        {
            return response()
                ->view($path_view, $this->data, 404);
        }

        if(file_exists(module_asset_path('appearance:resources/views/general/'.$this->data['theme_public']->value.'/content/'.$this->data['post']->post_type.'-'.$this->data['post']->getKey().'.blade.php')))
        {
            $path_view = 'appearance::general.'.$this->data['theme_public']->value.'.content.'.$this->data['post']->post_type.'-'.$this->data['post']->getKey();
        }
        elseif(file_exists(module_asset_path('appearance:resources/views/general/'.$this->data['theme_public']->value.'/content/'.$this->data['post']->post_type.'-'.$this->data['post']->post_slug.'.blade.php')))
        {
            $path_view = 'appearance::general.'.$this->data['theme_public']->value.'.content.'.$this->data['post']->post_type.'-'.$this->data['post']->post_slug;
        }
        elseif(file_exists(module_asset_path('appearance:resources/views/general/'.$this->data['theme_public']->value.'/content/'.$this->data['post']->post_slug.'.blade.php')))
        {
            $path_view = 'appearance::general.'.$this->data['theme_public']->value.'.content.'.$this->data['post']->post_slug;
        }
        else
        {
            $path_view = 'appearance::general.'.$this->data['theme_public']->value.'.content.post';
        }

        return response()
            ->view($path_view, $this->data);

    }

    public function page($slug)
    {
        $this->data['post'] = $this->post_m::with('postMeta')
                                                ->where(['post_slug' => $slug, 'post_type' => 'page', 'post_status' => 'publish'])
                                                ->first();

        $path_view = 'appearance::general.'.$this->data['theme_public']->value.'.templates.parent';

        if(empty($this->data['post']))
        {
            return response()
                ->view($path_view, $this->data, 404);
        }

        if(file_exists(module_asset_path('appearance:resources/views/general/'.$this->data['theme_public']->value.'/content/'.$this->data['post']->post_type.'-'.$this->data['post']->getKey().'.blade.php')))
        {
            $path_view = 'appearance::general.'.$this->data['theme_public']->value.'.content.'.$this->data['post']->post_type.'-'.$this->data['post']->getKey();
        }
        elseif(file_exists(module_asset_path('appearance:resources/views/general/'.$this->data['theme_public']->value.'/content/'.$this->data['post']->post_type.'-'.$this->data['post']->post_slug.'.blade.php')))
        {
            $path_view = 'appearance::general.'.$this->data['theme_public']->value.'.content.'.$this->data['post']->post_type.'-'.$this->data['post']->post_slug;
        }
        elseif(file_exists(module_asset_path('appearance:resources/views/general/'.$this->data['theme_public']->value.'/content/'.$this->data['post']->post_type.'.blade.php')))
        {
            $path_view = 'appearance::general.'.$this->data['theme_public']->value.'.content.'.$this->data['post']->post_type;
        }
        else
        {
            $path_view = 'appearance::general.'.$this->data['theme_public']->value.'.content.post';
        }

        return response()
            ->view($path_view, $this->data);
    }
}
