<?php

namespace App\Models\Eloquent;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    protected $fillable = [
        'name', 'url', 'problem_id'
    ];
}
