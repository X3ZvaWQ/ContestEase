<?php

namespace App\Http\Middleware\Answer;

use App\Models\Eloquent\Answer;
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
        if($request->has('answer_id')){
            $answer = Answer::find($request->answer_id);
            if(empty($answer)){
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
                'answer' => $answer
            ]);
        }
        return $next($request);
    }
}
