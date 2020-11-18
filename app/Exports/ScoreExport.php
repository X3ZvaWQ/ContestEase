<?php

namespace App\Exports;

use App\Models\Eloquent\Answer;
use App\Models\Eloquent\AnswerDispatch;
use App\Models\Eloquent\Problem;
use App\Models\Eloquent\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;

class ScoreExport implements FromCollection, WithStrictNullComparison
{
    private $data;

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $this->header();
        $users = User::where('id','>=',3)->where('id','<=',310)->get();
        $problems = Problem::get();
        $i = 1;
        foreach ($users as $user) {
            $i ++;
            $score_all = 0;
            foreach ($problems as $problem) {
                $answer = Answer::where('user_id',$user->id)->where('problem_id',$problem->id)->first();
                if(!empty($answer)){
                    if(empty($answer->content_old)){
                        $answer_content = $answer->content;
                    }else{
                        $answer_content = $answer->content . "\n============旧的提交============\n" . $answer->content_old;
                    }
                    $score = 1.0*$answer->dispatch->score/10;
                    $score_all += $score;
                    $yuejuanren = $answer->dispatch->user->name;
                    $this->data[$i][] = $answer_content;
                    $this->data[$i][] = $score;
                    $this->data[$i][] = $yuejuanren;
                }else{
                    $this->data[$i][] = null;
                    $this->data[$i][] = null;
                    $this->data[$i][] = null;
                }
            }
            $this->data[$i][] = $score_all;
            echo "第 {$i} 个用户处理完毕\n";
        }
        return collect($this->data);
    }

    private function header()
    {
        $this->data[0][] = '新柚杯 - 成绩导出';
        $problems = Problem::get();
        $this->data[1][] = '姓名';
        $this->data[1][] = '学号';
        $i = 1;
        foreach($problems as $problem) {
            $this->data[1][] = "第{$i}题答案";
            $this->data[1][] = "第{$i}题分数";
            $this->data[1][] = "第{$i}题阅卷人";
            $i ++;
        }
        $this->data[1][] = "总分";
        $i = 1;
        foreach(User::where('id','>=',3)->where('id','<=',310)->get() as $user){
            $i ++;
            $this->data[$i][] = $user->name;
            $this->data[$i][] = strtoupper(substr($user->email,0,9));
        }
    }
}
