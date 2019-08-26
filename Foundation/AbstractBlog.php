<?php

namespace Gdevilbat\SpardaCMS\Modules\Blog\Foundation;

use Illuminate\Http\Request;

use Gdevilbat\SpardaCMS\Modules\Blog\Contract\InterfaceBlog;
use Gdevilbat\SpardaCMS\Modules\Core\Http\Controllers\CoreController;

use Gdevilbat\SpardaCMS\Modules\Appearance\Http\Controllers\MenuController;

use Gdevilbat\SpardaCMS\Modules\Post\Entities\Post as Post_m;
use Gdevilbat\SpardaCMS\Modules\Taxonomy\Entities\Terms as Terms_m;
use Gdevilbat\SpardaCMS\Modules\Taxonomy\Entities\TermTaxonomy as TermTaxonomy_m;
use Gdevilbat\SpardaCMS\Modules\Core\Repositories\Repository;

use Auth;

/**
 * Class EloquentCoreRepository
 *
 * @package Gdevilbat\SpardaCMS\Modules\Core\Repositories\Eloquent
 */
abstract class AbstractBlog extends CoreController implements InterfaceBlog
{
    public function __construct()
    {
        parent::__construct();
        $this->post_m = new Post_m;
        $this->post_repository = new Repository(new Post_m);
        $this->term_terms_m = new Terms_m;
        $this->term_taxonomy_m = new TermTaxonomy_m;
        $this->term_taxonomy_repository = new Repository(new TermTaxonomy_m);
    }

    public function taxonomyPost(Request $request, $slug)
    {
        $path_view = 'appearance::general.'.$this->data['theme_public']->value.'.templates.parent';


        $menu_controller = new MenuController;
        $taxonomy = $menu_controller->getTaxonomyObject($slug);

        if(empty($taxonomy))
        {
            return response()
                ->view($path_view, $this->data, 404);
        }

        $depth = $this->getTaxonomyChildrensDepth($taxonomy);

        $whereHas = 'taxonomies';
        $query = $this->post_m->where(['post_type' => $this->getPostType()]);

        if(!Auth::check())
        {
            $query = $query->where('post_status',  'publish');
        }

        for ($d=1; $d < $depth -1 ; $d++) { 
            $whereHas = $whereHas.'.taxonomyParents';
        }

        $query->where(function($query) use ($taxonomy, $whereHas){
            $query->whereHas($whereHas, function($query) use ($taxonomy){
                           $query->where(\Gdevilbat\SpardaCMS\Modules\Taxonomy\Entities\TermTaxonomy::getPrimaryKey(), $taxonomy->getKey());
                    })
                  ->orWhereHas('taxonomies', function($query) use ($taxonomy){
                           $query->where(\Gdevilbat\SpardaCMS\Modules\Taxonomy\Entities\TermTaxonomy::getPrimaryKey(), $taxonomy->getKey());
                  });

        });


        $this->data['posts'] = $query->with($whereHas)->get();
        $this->data['taxonomy'] = $taxonomy;

        if($this->data['posts']->count() == 0)
        {
            return response()
                ->view($path_view, $this->data, 404);
        }

        if(file_exists(module_asset_path('appearance:resources/views/general/'.$this->data['theme_public']->value.'/content/'.$request->segment(1).'-'.$taxonomy->getKey().'.blade.php')))
        {
            $path_view = 'appearance::general.'.$this->data['theme_public']->value.'.content.'.$request->segment(1).'-'.$taxonomy->getKey();
        }
        elseif(file_exists(module_asset_path('appearance:resources/views/general/'.$this->data['theme_public']->value.'/content/'.$request->segment(1).'-'.str_slug($slug).'.blade.php')))
        {
            $path_view = 'appearance::general.'.$this->data['theme_public']->value.'.content.'.$request->segment(1).'-'.str_slug($slug);
        }
        elseif(file_exists(module_asset_path('appearance:resources/views/general/'.$this->data['theme_public']->value.'/content/'.$request->segment(1).'.blade.php')))
        {
            $path_view = 'appearance::general.'.$this->data['theme_public']->value.'.content.'.$request->segment(1);
        }
        else
        {
            $path_view = 'appearance::general.'.$this->data['theme_public']->value.'.content.taxonomy';
        }


        return response()
            ->view($path_view, $this->data);
    }

    public function getTaxonomyChildrensDepth(\Gdevilbat\SpardaCMS\Modules\Taxonomy\Entities\TermTaxonomy $taxonomy)
    {
        $depth = 0;

        foreach ($taxonomy->taxonomyChildrens as $children) 
        {
            $d = $this->getTaxonomyChildrensDepth($children);
            if($d > $depth){
                $depth = $d;
            }
        }

        return 1+$depth;
    }

    public function getCategoryWidget()
    {
        $menu = new MenuController;
        return json_decode(json_encode($menu->getTaxonomyNavbar()));
    }

    public function getPostType()
    {
        return $this->post_type;
    }

    public function throwError($code)
    {
        return response()
                ->view('appearance::general.'.$this->data['theme_public']->value.'.errors.'.$code, $this->data, $code);
    }
}
