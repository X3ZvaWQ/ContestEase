<?php

namespace App\Console;

use App\Models\Eloquent\Answer;
use App\Models\Eloquent\AnswerDispatch;
use App\Models\Eloquent\Problem;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            $progress = [];
            $all = Answer::count();
            $solved = AnswerDispatch::where('solved',true)->count();
            $marking = AnswerDispatch::where('solved',false)->where('expired_at', '>', date('Y-m-d H:i:s'))->count();
            $progress['all'] = [
                'title'         => '总进度',
                'progress'      => round(100.0*$solved/$all, 2),
                'all'           => $all,
                'solved'        => $solved,
                'marking'       => $marking,
                'group'         => '米娜桑'
            ];
            $i = 1;
            foreach(Problem::get() as $problem){
                $all = Answer::where('problem_id',$problem->id)->count();
                $solved = AnswerDispatch::where('problem_id',$problem->id)->where('solved',true)->count();
                $marking = AnswerDispatch::where('problem_id',$problem->id)->where('solved',false)->where('expired_at', '>', date('Y-m-d H:i:s'))->count();
                $progress['p'.$problem->id] = [
                    'title'         => "第{$i}题",
                    'progress'      => round(100.0*$solved/$all, 2),
                    'all'           => $all,
                    'solved'        => $solved,
                    'marking'       => $marking,
                    'group'         => $problem->group->name
                ];
                $i ++;
            }
            Cache::put('progress', json_encode($progress));
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
