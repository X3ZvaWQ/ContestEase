<?php

namespace App\Models\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Cache;

class Problem extends Model
{
    public static function modify($config)
    {
        if(isset($config['id'])){
            $problem = self::find($config['id']);
        }
        if(empty($announcement)){
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
}
