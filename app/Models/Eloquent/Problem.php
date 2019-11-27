<?php

namespace App\Models\Eloquent;

use Illuminate\Database\Eloquent\Model;
use App\Models\Eloquent\Answer;
use App\Models\Eloquent\AnswerDispatch;
use Cache;

class Problem extends Model
{
    public static function modify($config)
    {
        if(isset($config['id'])){
            $problem = self::find($config['id']);
        }
        if(empty($problem)){
            $problem = new Problem;
        }else{
            $problem->options()->delete();
        }
        $problem->title = $config['title'];
        $problem->content = $config['content'];
        $problem->save();
        if(!empty($config['options'])){
            $options = [];
            foreach($config['options'] as $option){
                $options[] = [
                    'problem_id' => $problem->id,
                    'content'    => $option,
                ];
            }
            $problem->options()->createMany($options);
        }
    }

    public static function fetch()
    {
        $result = [];
        $ret = static::get();
        foreach($ret as $problem) {
            $p = [
                'id'          => $problem->id,
                'title'       => $problem->title,
                'content'     => $problem->content,
                'images'      => [],
                'attachments' => [],
                'options'     => [],
            ];
            foreach ($problem->images as $image) {
                $p['images'][] = [
                    'name' => $image->name,
                    'url'  => $image->url,
                ];
            }
            foreach ($problem->attachments as $attachment) {
                $p['attachments'][] = [
                    'name' => $attachment->name,
                    'url'  => $attachment->url,
                ];
            }
            foreach ($problem->options as $option) {
                $p['options'][] = $option->content;

            }

            $result[] = $p;
        }
        return $result;
    }

    public function images()
    {
        return $this->hasMany('App\Models\Eloquent\Image');
    }

    public function attachments()
    {
        return $this->hasMany('App\Models\Eloquent\Attachment');
    }

    public function options()
    {
        return $this->hasMany('App\Models\Eloquent\Option');
    }

    public function answers()
    {
        return $this->hasMany('App\Models\Eloquent\Answer');
    }

    public function getMaxScoreAttribute()
    {
        $problem_score = ProblemScore::where('problem_id',$this->id)->first();
        return !empty($problem_score) ? $problem_score->max_score : 10;
    }

    public function getIsDispatchedAttribute()
    {
        $problem_dispatch = ProblemDispatch::where('problem_id',$this->id)->where('expired_at','>',date('Y-m-d H:i:s'))->first();
        return !empty($problem_dispatch);
    }

    public function getGroupAttribute()
    {
        $group_problem = GroupProblem::where('problem_id',$this->id)->first();
        if(!empty($group_problem)){
            return Group::find($group_problem->group_id);
        }
        return null;
    }

    public function getMarkDoneAttribute()
    {
        $all = Answer::where('problem_id',$this->id)->count();
        $solved = AnswerDispatch::where('problem_id',$this->id)->where('solved',true)->count();
        $marking = AnswerDispatch::where('problem_id',$this->id)->where('solved',false)->where('expired_at', '>', date('Y-m-d H:i:s'))->count();

        return $all == ($solved + $marking);
    }

    public function randAnswer()
    {
        $answers = $this->answers;
        $dispatched_answers = AnswerDispatch::where('problem_id',$this->id)->where(function ($query) {
            $query->where('solved', true)
                  ->orWhere('expired_at', '>', date('Y-m-d H:i:s'));
        })->get();
        $all_answer     = [];
        $disable_answer = [];
        foreach($answers as $answer)
            $all_answer[] = $answer->id;
        foreach($dispatched_answers as $dispatched_answer)
            $disable_answer[] = $dispatched_answer->answer->id;

        $available_answers = array_diff($all_answer,$disable_answer);
        if(empty($available_answers)){
            return null;
        }
        $answer = Answer::find($available_answers[array_rand($available_answers)]);
        return $answer;
    }
}

