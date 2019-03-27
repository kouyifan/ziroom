<?php

namespace Modules\Ziroom\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Ziroom\Repositories\Contracts\GetCommonDataInterface;

class ziroomWebCommon
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    private $common_data;

    public function __construct(GetCommonDataInterface $commonData)
    {
        $this->common_data = $commonData;
    }

    public function handle(Request $request, Closure $next)
    {

        \View::share('nav_data',$this->common_data->get_web_nav());
        return $next($request);
    }
}
