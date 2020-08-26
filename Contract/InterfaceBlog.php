<?php

namespace Gdevilbat\SpardaCMS\Modules\Blog\Contract;

use Illuminate\Http\Request;

/**
 * Interface CoreRepository
 * @package Modules\Core\Repositories
 */
interface InterfaceBlog
{
    /**
     * @param  int $id
     * @return $model
     */
    public function taxonomyPost(Request $request, $slug);

    /**
     * Update a resource
     * @param  $model
     * @param  array $data
     * @return $model
     */
    public function getPostType();

    /**
     * @param  int $id
     * @return $model
     */
    public function getCategoryWidget();

    public function getPostData(\Gdevilbat\SpardaCMS\Modules\Taxonomy\Entities\TermTaxonomy $taxonomy);

    /**
     * @param  int $id
     * @return $model
     */
    public function buildPostByTaxonomy(\Gdevilbat\SpardaCMS\Modules\Taxonomy\Entities\TermTaxonomy $taxonomy);

    /**
     * @param  int $id
     * @return $model
     */
    public function getTaxonomyChildrensDepth(\Gdevilbat\SpardaCMS\Modules\Taxonomy\Entities\TermTaxonomy $taxonomy);

    //Error Code
    public function throwError($code);
}
