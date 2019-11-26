<?php

namespace App;

use App\Models\Eloquent\Answer;
use App\Models\Eloquent\Problem;
use App\Models\Eloquent\GroupProblem;



class Cmd
{
    public static function gogogo()
    {
        $insert_log = storage_path('app').'/result_insert.json';
        $insert_datas = json_decode(file_get_contents($insert_log),true);
        $i = [0,0];
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
                }else{
                    $answer->content_old  = $insert['content'];
                    $answer->save();
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
        echo "{$i[0]} inserts {$i[1]} no gogogo \n";
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
        foreach(Problem::get() as $p)
        {
            GroupProblem::insert([
                'group_id' => 1,
                'problem_id' => $p->id
            ]);
        } 
    }
}
