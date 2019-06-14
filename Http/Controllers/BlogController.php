<?php

namespace Gdevilbat\SpardaCMS\Modules\Blog\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use  Gdevilbat\SpardaCMS\Modules\Core\Http\Controllers\CoreController;
use  Gdevilbat\SpardaCMS\Modules\Appearance\Http\Controllers\MenuController;

use Gdevilbat\SpardaCMS\Modules\Post\Entities\Post as Post_m;
use Gdevilbat\SpardaCMS\Modules\Taxonomy\Entities\TermTaxonomy as TermTaxonomy_m;
use Gdevilbat\SpardaCMS\Modules\Core\Repositories\Repository;

class BlogController extends CoreController
{
    public function __construct()
    {
        parent::__construct();
        $this->post_m = new Post_m;
        $this->post_repository = new Repository(new Post_m);
        $this->term_taxonomy_m = new TermTaxonomy_m;
        $this->term_taxonomy_repository = new Repository(new TermTaxonomy_m);
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $this->data['post'] = $this->post_m->where('post_slug', 'homepage')->first();
        $path_view = 'blog::general.'.$this->data['theme_public']->value.'.templates.parent';

        if(empty($this->data['post']))
        {
            return response()
                ->view($path_view, $this->data, 404);
        }

        if(file_exists(module_asset_path('blog:resources/views/general/'.$this->data['theme_public']->value.'/content/homepage.blade.php')))
        {
            $path_view = 'blog::general.'.$this->data['theme_public']->value.'.content.homepage';
        }
        elseif(file_exists(module_asset_path('blog:resources/views/general/'.$this->data['theme_public']->value.'/content/page.blade.php')))
        {
            $path_view = 'blog::general.'.$this->data['theme_public']->value.'.content.page';
        }
        else
        {
            $path_view = 'blog::general.'.$this->data['theme_public']->value.'.content.post';
        }

        return response()
                ->view($path_view, $this->data);
    }

    public function blog($year, $month, $slug)
    {
        $this->data['post'] = $this->post_m::with('postMeta')
                                            ->where(['post_slug' => $slug, 'post_type' => 'post', 'post_status' => 'publish'])
                                            ->whereYear('created_at', $year)
                                            ->whereMonth('created_at', $month)
                                            ->first();

        $this->data['recent_posts'] = $this->post_m->with('postMeta')
                                            ->where(['post_type' => 'post', 'post_status' => 'publish'])
                                            ->where('id', '!=', $this->data['post']->id)
                                            ->latest('created_at')
                                            ->limit(5)
                                            ->get();

        $menu = new MenuController;
        $this->data['category_widget'] = json_decode(json_encode($menu->getTaxonomyNavbar()));

        $path_view = 'blog::general.'.$this->data['theme_public']->value.'.templates.parent';

        if(empty($this->data['post']))
        {
            return response()
                ->view($path_view, $this->data, 404);
        }

        if(file_exists(module_asset_path('blog:resources/views/general/'.$this->data['theme_public']->value.'/content/'.$this->data['post']->post_type.'-'.$this->data['post']->id.'.blade.php')))
        {
            $path_view = 'blog::general.'.$this->data['theme_public']->value.'.content.'.$this->data['post']->post_type.'-'.$this->data['post']->id;
        }
        elseif(file_exists(module_asset_path('blog:resources/views/general/'.$this->data['theme_public']->value.'/content/'.$this->data['post']->post_type.'-'.$this->data['post']->post_slug.'.blade.php')))
        {
            $path_view = 'blog::general.'.$this->data['theme_public']->value.'.content.'.$this->data['post']->post_type.'-'.$this->data['post']->post_slug;
        }
        elseif(file_exists(module_asset_path('blog:resources/views/general/'.$this->data['theme_public']->value.'/content/'.$this->data['post']->post_slug.'.blade.php')))
        {
            $path_view = 'blog::general.'.$this->data['theme_public']->value.'.content.'.$this->data['post']->post_slug;
        }
        else
        {
            $path_view = 'blog::general.'.$this->data['theme_public']->value.'.content.post';
        }

        return response()
            ->view($path_view, $this->data);

    }

    public function page($slug)
    {
        $this->data['post'] = $this->post_m::with('postMeta')
                                                ->where(['post_slug' => $slug, 'post_type' => 'page', 'post_status' => 'publish'])
                                                ->first();

        $path_view = 'blog::general.'.$this->data['theme_public']->value.'.templates.parent';

        if(empty($this->data['post']))
        {
            return response()
                ->view($path_view, $this->data, 404);
        }

        if(file_exists(module_asset_path('blog:resources/views/general/'.$this->data['theme_public']->value.'/content/'.$this->data['post']->post_type.'-'.$this->data['post']->id.'.blade.php')))
        {
            $path_view = 'blog::general.'.$this->data['theme_public']->value.'.content.'.$this->data['post']->post_type.'-'.$this->data['post']->id;
        }
        elseif(file_exists(module_asset_path('blog:resources/views/general/'.$this->data['theme_public']->value.'/content/'.$this->data['post']->post_type.'-'.$this->data['post']->post_slug.'.blade.php')))
        {
            $path_view = 'blog::general.'.$this->data['theme_public']->value.'.content.'.$this->data['post']->post_type.'-'.$this->data['post']->post_slug;
        }
        elseif(file_exists(module_asset_path('blog:resources/views/general/'.$this->data['theme_public']->value.'/content/'.$this->data['post']->post_type.'.blade.php')))
        {
            $path_view = 'blog::general.'.$this->data['theme_public']->value.'.content.'.$this->data['post']->post_type;
        }
        else
        {
            $path_view = 'blog::general.'.$this->data['theme_public']->value.'.content.post';
        }

        return response()
            ->view($path_view, $this->data);
    }

    public function taxonomyPost(Request $request, $slug)
    {
        $path_view = 'blog::general.'.$this->data['theme_public']->value.'.templates.parent';


        $menu_controller = new MenuController;
        $term = $menu_controller->getTaxonomyObject($slug);

        if(empty($term))
        {
            return response()
                ->view($path_view, $this->data, 404);
        }

        $this->data['posts'] = $this->post_m->whereHas('taxonomies', function($query) use ($term){
                                       $query->where($this->term_taxonomy_m->getTable().'.id', $term->id);
                                    })
                                    ->get();

        if($this->data['posts']->count() == 0)
        {
            return response()
                ->view($path_view, $this->data, 404);
        }

        if(file_exists(module_asset_path('blog:resources/views/general/'.$this->data['theme_public']->value.'/content/'.$request->segment(1).'-'.$term->id.'.blade.php')))
        {
            $path_view = 'blog::general.'.$this->data['theme_public']->value.'.content.'.$request->segment(1).'-'.$term->id;
        }
        elseif(file_exists(module_asset_path('blog:resources/views/general/'.$this->data['theme_public']->value.'/content/'.$request->segment(1).'-'.str_slug($slug).'.blade.php')))
        {
            $path_view = 'blog::general.'.$this->data['theme_public']->value.'.content.'.$request->segment(1).'-'.str_slug($slug);
        }
        elseif(file_exists(module_asset_path('blog:resources/views/general/'.$this->data['theme_public']->value.'/content/'.$request->segment(1).'.blade.php')))
        {
            $path_view = 'blog::general.'.$this->data['theme_public']->value.'.content.'.$request->segment(1);
        }
        else
        {
            $path_view = 'blog::general.'.$this->data['theme_public']->value.'.content.taxonomy';
        }


        return response()
            ->view($path_view, $this->data);
    }
}
