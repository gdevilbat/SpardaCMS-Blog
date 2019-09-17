<?php

namespace Gdevilbat\SpardaCMS\Modules\Blog\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Gdevilbat\SpardaCMS\Modules\Blog\Foundation\AbstractBlog;
use Gdevilbat\SpardaCMS\Modules\Appearance\Http\Controllers\MenuController;

use Google_Client;
use Google_Service_Customsearch;

use Auth;

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
        $KEY_FILE_LOCATION = base_path('spardastore-e62170df77a5.json');

        $client = new Google_Client();
        $client->setApplicationName("Nama Aplikasi");
        $apiKey = "AIzaSyD4Mt5fWxANjaOSdUsx2g3tWoIKLjq0s_4"; // masukkan API Key
        $client->setAuthConfig($KEY_FILE_LOCATION);
        $client->setScopes(['https://www.googleapis.com/auth/cse']);
        //$client->setDeveloperKey($apiKey);

        $service = new Google_Service_Customsearch($client);
        $arrOptions = array();
        $arrOptions['cx'] = '005608933390545725656:nzdzqcozwxo'; // masukkan Search Engine ID
        $q = 'laptop murah'; // contoh keyword yang ingin dicari
        $result = $service->cse->listCse($q,$arrOptions);

        $this->print_r($result->items);
        die;

        $query = $this->post_m::with('postMeta', 'author')
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

        $this->data['post_categories'] = $this->data['post']->load(['taxonomies' => function($query){
                                            $query->where('taxonomy', 'category');
                                        }, 'taxonomies.term'])->taxonomies;

        $this->data['post_tags'] = $this->data['post']->load(['taxonomies' => function($query){
                                            $query->where('taxonomy', 'tag');
                                        }, 'taxonomies.term'])->taxonomies;


        /*===================================
        =            Recent Post            =
        ===================================*/
        
            $this->data['recent_posts'] = $this->post_m->with('postMeta')
                                                ->where(['post_type' => 'post', 'post_status' => 'publish'])
                                                ->where(\Gdevilbat\SpardaCMS\Modules\Post\Entities\Post::getPrimaryKey(), '!=', $this->data['post']->getKey())
                                                ->latest('created_at')
                                                ->limit(3)
                                                ->get();
        
        /*=====  End of Recent Post  ======*/
        

        /*===========================================
        =            Related Post            =
        ===========================================*/
        
            $query = $this->buildPostByTaxonomy($this->data['post_categories']->first())
                                                ->with('postMeta')
                                                ->where(\Gdevilbat\SpardaCMS\Modules\Post\Entities\Post::getPrimaryKey(), '!=', $this->data['post']->getKey())
                                                ->inRandomOrder()
                                                ->limit(3);

            $this->data['related_posts'] = $query->get();
        
        /*=====  End of Related Post  ======*/


        /*===========================================
        =            Recomended Post            =
        ===========================================*/
        
            $query = $this->post_m->with('postMeta')
                                                ->where(['post_type' =>  $this->getPostType()])
                                                ->where(\Gdevilbat\SpardaCMS\Modules\Post\Entities\Post::getPrimaryKey(), '!=', $this->data['post']->getKey())
                                                ->inRandomOrder()
                                                ->limit(3);

            if(!Auth::check())
            {
                $query = $query->where('post_status',  'publish');
            }

            $this->data['recomended_posts'] = $query->get();
        
        /*=====  End of Recomended Post  ======*/

        $path_view = 'appearance::general.'.$this->data['theme_public']->value.'.templates.parent';

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

        $path_view = 'appearance::general.'.$this->data['theme_public']->value.'.templates.parent';

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
