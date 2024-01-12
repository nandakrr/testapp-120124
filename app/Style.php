<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Style extends Model
{
    protected $table = 'style';
    public $timestamps = false;
    protected $fillable = [
        'level_name', 'description', 'studio_id',
    ];
}
