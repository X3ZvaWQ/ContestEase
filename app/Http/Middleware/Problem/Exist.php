<?php

namespace App\Http\Middleware\Problem;

use App\Models\Eloquent\Problem;
use Closure;

class Exist
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
        if($request->has('problem_id')){
            $problem = Problem::find($request->problem_id);
            if(empty($problem)){
                if($request->method() == 'GET') {
                    return redirect('/');
                }else{
                    return response()->json([
                        'ret'  => 404,
                        'desc' => 'not found!',
                        'data' => ''
                    ], 403);
                }
            }
            $request->merge([
                'problem' => $problem
            ]);
        }
        return $next($request);
    }
}
