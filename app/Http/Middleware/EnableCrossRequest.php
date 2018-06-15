<?php

namespace App\Http\Middleware;

use Closure;
use Response;
use Log;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class EnableCrossRequest {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Headers', 'Origin, Content-Type, Accept, Cookie, Authorization,X-XSRF-TOKEN');
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PATCH, PUT, OPTIONS, DELETE');
        $response->headers->set('Access-Control-Expose-Headers', 'Authorization');

        return $response;
    }

}
