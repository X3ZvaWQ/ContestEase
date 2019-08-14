<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Eloquent\Contest;
use App\Models\Eloquent\Answer;

class ContestController extends Controller
{
    public function status()
    {
        return response()->json([
            'ret'  => 200,
            'desc' => 'successful',
            'data' => Contest::status(),
        ]);
    }

    public function submitted(Request $request)
    {
        $user_id = $request->user()->id;
        $answers = Answer::fetch($user_id);
        foreach ($answers as &$answer) {
            foreach ($answer as $key => &$value) {
                if(is_null($value)){
                    $value = '';
                }
            }
        }
        return response()->json([
            'ret'  => 200,
            'desc' => 'successful',
            'data' => $answers,
        ]);
    }

    public function submit(Request $request)
    {
        $request->validate([
            'problem_id' => 'required|integer',
        ]);

        $config = [
            'problem_id' => $request->input('problem_id'),
            'user_id'    => $request->user()->id,
            'content'    => $request->input('content'),
            'option'     => $request->input('option')
        ];

        Answer::submit($config);
        return response()->json([
            'ret'   => 200,
            'desc'  => 'successful',
            'data'  => null
        ]);
    }

    public function modify(Request $request)
    {
        $request->validate([
            'start' => 'required|integer',
            'end'   => 'required|integer',
            'required|integer'  => 'string',
        ]);
        $name = $request->input('name',null);
        $config = [
            'begin_time' => $request->input('start'),
            'end_time'   => $request->input('end'),
        ];
        if(!empty($name)){
            $config['name'] = $name;
        }
        Contest::set($config);
        return response()->json([
            'ret'   => 200,
            'desc'  => 'successful',
            'data'  => null
        ]);
    }
}
