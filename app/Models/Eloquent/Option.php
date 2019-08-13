<?php

namespace App\Models\Eloquent;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    //
    protected $fillable = [
        'content', 'problem_id'
    ];
}
