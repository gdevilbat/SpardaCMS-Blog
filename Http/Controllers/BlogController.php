<?php

namespace Gdevilbat\SpardaCMS\Modules\Blog\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use  Gdevilbat\SpardaCMS\Modules\Core\Http\Controllers\CoreController;
use  Gdevilbat\SpardaCMS\Modules\Appearance\Http\Controllers\MenuController;

class BlogController extends CoreController
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('blog::general.'.$this->data['theme_public']->value.'.templates.parent', $this->data);
    }
}
