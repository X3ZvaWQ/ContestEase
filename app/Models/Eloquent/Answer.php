<?php

namespace App\Models\Eloquent;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    public $timestamps = false;
    protected $guarded = [];

    public static function fetch($user_id)
    {
        $ret = static::where([
            'user_id' =>$user_id,
        ])->get();
        if(empty($ret)){
            return [];
        }else{
            $result = [];
            foreach ($ret as $answer) {
                $result[] = [
                    'id'          => $answer->id,
                    'problem_id'  => $answer->problem_id,
                    'option'      => $answer->option,
                    'content'     => $answer->content,
                    'update_time' => strtotime($answer->updated_at),
                ];
            }
            return $result;
        }
    }

    public static function submit($config)
    {
        $answer = static::where([
            'user_id' => $config['user_id'],
            'problem_id' => $config['problem_id']
        ])->first();

        if(empty($answer)){
            $answer = new Answer;
        }

        $answer->user_id = $config['user_id'];
        $answer->problem_id = $config['problem_id'];
        $answer->content = $config['content'];
        if(isset($config['option'])){
            $answer->option = $config['option'];
        }

        $answer->save();
    }
}
