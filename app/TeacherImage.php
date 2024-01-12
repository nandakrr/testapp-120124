<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TeacherImage extends Model
{
    public function teacher()
    {
        return $this->belongsTo('App\Teacher', 'teacher_id');
    }
}
