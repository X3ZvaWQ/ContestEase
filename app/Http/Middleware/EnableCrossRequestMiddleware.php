<?php

namespace App\Http\Middleware;

use Closure;

class EnableCrossRequestMiddleware
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
        if($request->method() != 'OPTIONS'){
            header('Access-Control-Allow-Origin: *');
            header("Access-Control-Allow-Credentials: false");
            header("Access-Control-Allow-Methods: *");
            header("Access-Control-Allow-Headers: *");
            header("Access-Control-Expose-Headers: *");
        }else{
            header('Access-Control-Allow-Origin: *');
            header("Access-Control-Allow-Credentials: false");
            header("Access-Control-Allow-Methods: POST");
            header("Access-Control-Allow-Headers: Accept,Content-Type,Authorization");
            header("Access-Control-Expose-Headers: *");
        }

        return $next($request);
    }
}
