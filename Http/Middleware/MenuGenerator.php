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
        $navbars = new MenuController;
        $navbars = json_decode(json_encode($navbars->getNavbars()));

        \View::share(
                [
                'navbars' => $navbars,
            ]
        );

        return $next($request);
    }
}
