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

use Google_Client;
use Google_Service_Customsearch;

use Auth;

/**
 * Class EloquentCoreRepository
 *
 * @package Gdevilbat\SpardaCMS\Modules\Core\Repositories\Eloquent
 */
abstract class AbstractBlog extends CoreController implements InterfaceBlog
{
    public function __construct(\Gdevilbat\SpardaCMS\Modules\Post\Repositories\PostRepository $post_repository)
    {
        parent::__construct();
        $post_model = config('cms-post.Model');
        $this->post_m = new $post_model;
        $this->post_repository = $post_repository;
        $this->term_terms_m = new Terms_m;
        $this->term_taxonomy_m = new TermTaxonomy_m;
        $this->term_taxonomy_repository = new Repository(new TermTaxonomy_m, resolve(\Gdevilbat\SpardaCMS\Modules\Role\Repositories\Contract\AuthenticationRepository::class));
    }

    public function taxonomyPost(Request $request, $slug)
    {
        $path_view = 'appearance::general.'.$this->data['theme_public']->value.'.templates.parent';


        $menu_controller = new MenuController;
        $taxonomy = $menu_controller->getTaxonomyObject($slug);

        if(empty($taxonomy))
        {
            return response()
                ->view('appearance::general.'.$this->data['theme_public']->value.'.errors.404', $this->data, 404);
        }

        $this->data['posts_builder'] = $this->buildPostByTaxonomy($taxonomy)->latest();
        $this->data['taxonomy'] = $taxonomy;

        if($this->data['posts_builder']->count() == 0)
        {
            return response()
                ->view('appearance::general.'.$this->data['theme_public']->value.'.errors.404', $this->data, 404);
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

    public function search(Request $request)
    {
        $KEY_FILE_LOCATION = base_path(env('GOOGLE_KEY'));

        $client = new Google_Client();
        $client->setApplicationName(env('APP_NAME'));
        //$apiKey = "AIzaSyD4Mt5fWxANjaOSdUsx2g3tWoIKLjq0s_4"; // masukkan API Key
        $client->setAuthConfig($KEY_FILE_LOCATION);
        $client->setScopes(['https://www.googleapis.com/auth/cse']);
        //$client->setDeveloperKey($apiKey);

        $service = new Google_Service_Customsearch($client);
        $arrOptions = array();
        $arrOptions['cx'] = env('SEARCH_ENGINE_ID'); // masukkan Search Engine ID
        $q = $request->has('query') ? $request->input('query') : '' ;
        $result = $service->cse->listCse($q,$arrOptions);

        $this->data['googles'] = $result->items;

        $this->data['posts_builder'] = Post_m::where(function($query) use ($q){
                                            $query->where('post_title', 'LIKE', '%'.$q.'%')
                                                 ->orWhere('post_content', 'LIKE', '%'.$q.'%');
                                        })
                                     ->where(function($query){
                                        $query->where('post_type', 'post')
                                              ->orWhere('post_type', 'product');
                                     })
                                     ->where('post_status', 'publish')
                                     ->latest();

        return response()
                ->view('appearance::general.'.$this->data['theme_public']->value.'.content.search', $this->data);
    }

    public function buildPostByTaxonomy(\Gdevilbat\SpardaCMS\Modules\Taxonomy\Entities\TermTaxonomy $taxonomy)
    {
    	$depth = $this->getTaxonomyChildrensDepth($taxonomy);

        $whereHas = 'taxonomies';
        $query = $this->post_repository->setModel($this->post_m)->with([]);

        if(!Auth::check())
        {
            $query = $query->where('post_status',  'publish');
        }

        for ($d=1; $d < $depth -1 ; $d++) { 
            $whereHas = $whereHas.'.parent';
        }

        $query = $query->where(function($query) use ($taxonomy, $whereHas){
                        $query->whereHas($whereHas, function($query) use ($taxonomy){
                                       $query->where(\Gdevilbat\SpardaCMS\Modules\Taxonomy\Entities\TermTaxonomy::getPrimaryKey(), $taxonomy->getKey());
                                })
                              ->orWhereHas('taxonomies', function($query) use ($taxonomy){
                                       $query->where(\Gdevilbat\SpardaCMS\Modules\Taxonomy\Entities\TermTaxonomy::getPrimaryKey(), $taxonomy->getKey());
                              });

        });

        return $query->with($whereHas);
    }

    public function getTaxonomyChildrensDepth(\Gdevilbat\SpardaCMS\Modules\Taxonomy\Entities\TermTaxonomy $taxonomy)
    {
        $depth = 0;

        foreach ($taxonomy->childrens as $children) 
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
