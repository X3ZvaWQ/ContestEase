<?php

namespace App\Models\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Cache;

class Announcement extends Model
{
    public static function set($config)
    {
        if(isset($config['id'])){
            $announcement = self::find($config['id']);
        }
        if(empty($announcement)){
            $announcement = new Announcement;
        }
        $announcement->title = $config['title'];
        $announcement->content = $config['content'];
        $announcement->save();
        $announcements = self::fetch();
        Cache::put('notices_md5',json_encode($announcements));
    }

    public static function fetch()
    {
        $ret = static::get();
        if(empty($ret)){
            return [];
        }else{
            $result = [];
            foreach ($ret as $announcement) {
                $result[] = [
                    'id'       => $announcement->id,
                    'title'    => $announcement->title,
                    'content'  => $announcement->content,
                    'time'     => strtotime($announcement->created_at)
                ];
            }
            return $result;
        }
    }
}
