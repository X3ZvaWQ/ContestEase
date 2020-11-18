<?php

namespace App\Models\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AnswerDispatch extends Model
{
    use SoftDeletes;
    //

    public $guarded = [];

    public function answer()
    {
        return $this->belongsTo('App\Models\Eloquent\Answer');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\Eloquent\User');
    }
}
