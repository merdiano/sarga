<?php

namespace Sarga\API\Http\Middleware;

use Closure;
use Webkul\Core\Repositories\LocaleRepository;

class Scrap
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!$request->hasHeader('Authorization') || $request->header('Authorization') != '0a358dd1-2b07-4cdf-9d9a-a68dac6bb5fc') {
            return response()->json(['errors'=>"Unauthorized request"],401);
        }

        return $next($request);
    }
}
