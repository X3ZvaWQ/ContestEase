<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contest extends Model
{
    public $timestamps = false;

    public static function set($config)
    {
        $contest = static::find(1);
        if(empty($contest)){
            $contest = new Contest;
            $contest->id = 1;
        }
        if(isset($config['name'])){
            $contest->name = $config['name'];
        }
        $contest->begin_time = $config['begin_time'];
        $contest->end_time = $config['end_time'];
        $contest->save();
    }

    public static function time()
    {
        $contest = self::find(1);
        return [
            'start' => strtotime($contest->begin_time),
            'end'   => strtotime($contest->end_time),
        ];
    }
}
