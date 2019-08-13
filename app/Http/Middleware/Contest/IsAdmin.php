<?php

namespace App\Http\Middleware\Contest;

use Closure;

class IsAdmin
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
        if(!empty($request->user()) && $request->user()->is_admin){
            return $next($request);
        }else{
            return response()->json([
                'ret'  => '403',
                'desc' => 'Forbidden.'
            ]);
        }

    }
}
