<?php

namespace App\Models\Eloquent;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    public function problems()
    {
        return $this->hasManyThrough('App\Models\Eloquent\Problem','App\Models\Eloquent\GroupProblem','group_id','id','id','problem_id');
    }

    public function members()
    {
        return $this->hasManyThrough('App\Models\Eloquent\User','App\Models\Eloquent\GroupMember','group_id','id','id','user_id');
    }
}
