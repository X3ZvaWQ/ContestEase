<?php

namespace App;

use App\Models\Eloquent\Answer;
use App\Models\Eloquent\AnswerDispatch;
use App\Models\Eloquent\Problem;
use App\Models\Eloquent\GroupProblem;

class Cmd
{
    public static function gogogo()
    {
        $insert_log = storage_path('app').'/result_insert.json';
        $insert_datas = json_decode(file_get_contents($insert_log),true);
        $i = [0,0,0,0];
        foreach($insert_datas as $insert) {
            $i[0] ++;
            $answer = Answer::where([
                'user_id'    => $insert['user_id'],
                'problem_id' => $insert['problem_id'],
            ])->first();
            if(!empty($answer)) {
                if(strtotime($answer['updated_at']) <= strtotime($insert['created_at'])) {
                    $answer->content      = $insert['content'];
                    $answer->updated_at   = $insert['created_at'];
                    $answer->save();
                    $i[2] ++;
                }else{
                    $answer->content_old  = $insert['content'];
                    $answer->save();
                    $i[3] ++;
                }
            }else{
                Answer::create([
                    'user_id'    => $insert['user_id'],
                    'problem_id' => $insert['problem_id'],
                    'content'    => $insert['content'],
                    'created_at' => $insert['created_at'],
                    'updated_at' => $insert['updated_at']
                ]);
            }
        }
        echo "{$i[0]} inserts {$i[1]} no gogogo {$i[2]} aaa {$i[3]} bbb \n";
        $update_log = storage_path('app').'/result_update.json';
        $update_datas = json_decode(file_get_contents($update_log),true);
        $i = [0,0,0];
        foreach($update_datas as $update) {
            $i[0] ++;
            $answer = Answer::find($update['answer_id']);
            if(!empty($answer)) {
                if(strtotime($answer['updated_at']) <= strtotime($update['updated_at'])) {
                    $answer->content    = $update['content'];
                    $answer->updated_at = $update['updated_at'];
                    $answer->save();
                }else{
                    $i[2] ++;
                }
            } else {
                $i[1] ++;
            }
        }
        echo "{$i[0]} updates {$i[1]} no gogogo {$i[2]} oudated \n";
    }

    public static function aaa()
    {
        $i = 0;
        $answers = Answer::get();
        foreach($answers as $answer){
            $dispatch = $answer->dispatch;
            $last_dispatch = AnswerDispatch::find($dispatch->id - 1);
            if(empty($dispatch)) {
                $i ++;
                echo "{$i}: pid {$answer->problem_id}  aid {$answer->id}  not found \n";
                continue;
            }
            if($answer->content === null) {
                $i ++;
                if(empty($last_dispatch)){
                    echo "{$i}: pid {$dispatch->problem_id}  aid {$dispatch->answer_id}  uid {$answer->user_id} score {$dispatch->score} last_score {None}\n";
                }else{
                    echo "{$i}: pid {$dispatch->problem_id}  aid {$dispatch->answer_id}  uid {$answer->user_id} score {$dispatch->score} last_score {$last_dispatch->score}\n";
                }
            }
        }
    }
}
