<?php 
namespace App\Http\Middleware;

use Closure;

class AfterMiddleware {

    public function handle($request, Closure $next)
    {
        $response = $next($request);
        $response->header('Access-Control-Allow-Origin', '*')->header('Access-Control-Allow-Methods', 'GET, PUT, POST, DELETE, OPTIONS');


        return $response;
    }
}