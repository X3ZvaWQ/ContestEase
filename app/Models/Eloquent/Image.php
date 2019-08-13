<?php

namespace App\Models\Eloquent;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = [
        'name', 'url', 'problem_id'
    ];
}
