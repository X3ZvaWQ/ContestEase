<?php

namespace App\Http\Controllers;

use App\Models\Eloquent\Answer;
use App\Models\Eloquent\AnswerDispatch;
use App\Models\Eloquent\Problem;
use Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MarkController extends Controller
{
    public function index()
    {
        return view('mark');
    }

    public function mark(Request $request)
    {
        $user = Auth::user();
        $answer = $request->answer;
        $score = $request->pb_score;
        $score = doubleval($score);
        if($score < 0 || $score > $answer->problem->max_score) {
            return response()->json([
                'ret'     => 403,
                'desc'    => '非法的分数',
                'data'    => null
            ], 200);
        }
        $answer_dispatch = AnswerDispatch::where([
            'user_id'   => $user->id,
            'answer_id' => $answer->id,
            'solved'    => false
        ])->where('expired_at','>',date('Y-m-d H:i:s'))->first();
        if(empty($answer_dispatch)) {
            return response()->json([
                'ret'     => 403,
                'desc'    => '您没有被指派批改此题或该答案您的批改期限已过(30分钟),请刷新该页面',
                'data'    => null
            ], 200);
        }
        $answer_dispatch->solved = true;
        $answer_dispatch->score  = $score*10;
        $answer_dispatch->save();
        return response()->json([
            'ret'     => 200,
            'desc'    => '成功',
            'data'    => null
        ], 200);
    }

    public function progress()
    {
        $progress = json_decode(Cache::get('progress'), true);
        if(empty($progress)) {
            return response()->json([
                'ret'  => 200,
                'desc' => 'success',
                'data' => []
            ], 200);
        }
        return response()->json([
            'ret'  => 200,
            'desc' => 'success',
            'data' => json_decode(Cache::get('progress'), true)
        ], 200);
    }

    public function request(Request $request)
    {
        $user = Auth::user();
        $dispatched_answer = AnswerDispatch::where([
            'user_id' => $user->id,
            'solved'  => false
        ])->where('expired_at','>',date('Y-m-d H:i:s'))->first();

        if(!empty($dispatched_answer)){
            if(!$request->force){
                return response()->json([
                    'ret'     => 200,
                    'desc'    => 'success',
                    'data'    => [
                        'answer_id'  => $dispatched_answer->answer->id,
                        'problem'    => $dispatched_answer->answer->problem->content,
                        'answer'     => $dispatched_answer->answer->content,
                        'old_answer' => $dispatched_answer->answer->content_old,
                        'max_score'  => $dispatched_answer->answer->problem->max_score,
                    ]
                ], 200);
            }else{
                $dispatched_answer->delete();
            }
        }

        if(!empty($request->problem)){
            $problem = $request->problem;
            $answer = $problem->randAnswer($problem);
            if(empty($answer)) {
                return response()->json([
                    'ret'     => 233,
                    'desc'    => '这题已经改完啦，看看别的题目吧？',
                    'data'    => null
                ], 200);
            }
        }else{
            $group = $user->group;
            $problems = $group->problems;
            foreach($problems as $problem){
                if(!$problem->mark_done){
                    $answer = $problem->randAnswer($problem);
                    break;
                }
            }
            if(empty($answer)) {
                return response()->json([
                    'ret'     => 233,
                    'desc'    => '组内题目改完啦，要不看看别的组的题目？点击左边的进度条即可发起批改请求哦',
                    'data'    => null
                ], 200);
            }
        }

        AnswerDispatch::create([
            'answer_id'     => $answer->id,
            'problem_id'    => $answer->problem->id,
            'user_id'       => $user->id,
            'dispatched_at' => date('Y-m-d H:i:s'),
            'expired_at'    => date('Y-m-d H:i:s',time() + 1800)
        ]);
        return response()->json([
            'ret'     => 200,
            'desc'    => 'success',
            'data'    => [
                'answer_id'  => $answer->id,
                'problem'    => $answer->problem->content,
                'answer'     => $answer->content,
                'old_answer' => $answer->content_old,
                'max_score'  => $answer->problem->max_score,
            ]
        ], 200);

    }
}
