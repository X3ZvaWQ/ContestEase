<?php

namespace App\Models\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Cache;

class Contest extends Model
{
    public $timestamps = false;

    public static function set($config)
    {
        $contest = static::find(1);
        if(empty($contest)){
            $contest = new Contest;
            $contest->name = '计算姬基础知识大赛';
            $contest->id = 1;
        }
        if(isset($config['name'])){
            $contest->name = $config['name'];
        }
        $contest->begin_time = date('Y-m-d H:i:s',$config['begin_time']);
        $contest->end_time   = date('Y-m-d H:i:s',$config['end_time']);
        $contest->save();
    }

    public static function status()
    {
        $contest = static::find(1);
        if(empty($contest)){
            return [
                'name'         => '',
                'start'        => 0,
                'end'          => 0,
                'problems_md5' => Cache::get('problems_md5',''),
                'notices_md5'  => Cache::get('notices_md5',''),
            ];
        }
        return [
            'name'         => $contest->name,
            'start'        => strtotime($contest->begin_time),
            'end'          => strtotime($contest->end_time),
            'problems_md5' => Cache::get('problems_md5',''),
            'notices_md5'  => Cache::get('notices_md5',''),
        ];
    }

    public static function running()
    {
        $contest = static::find(1);
        if(empty($contest)){
            return false;
        }
        $begin_time = strtotime($contest->begin_time);
        $end_time   = strtotime($contest->end_time);
        $now_time   = time();
        if($now_time >= $begin_time && $now_time <= $end_time){
            return true;
        }
        return false;
    }
}
