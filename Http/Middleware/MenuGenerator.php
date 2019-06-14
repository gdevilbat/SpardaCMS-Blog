<?php

namespace Gdevilbat\SpardaCMS\Modules\Blog\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

use  Gdevilbat\SpardaCMS\Modules\Appearance\Http\Controllers\MenuController;

class MenuGenerator
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $menu = new MenuController;

        $navbars = json_decode(json_encode($menu->getNavbars()));
        $post_navbars = json_decode(json_encode($menu->getPostNavbar()));

        \View::share(
                [
                'navbars' => $navbars,
                'post_navbars' => $post_navbars,
            ]
        );

        return $next($request);
    }
}
