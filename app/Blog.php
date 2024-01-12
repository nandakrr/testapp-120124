<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Blog extends Model
{
    protected $table = 'blogs';

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'description'  => 'array',
    ];

    protected $slugField   = 'slug';
    protected $slugFromField  = 'title';

      
}