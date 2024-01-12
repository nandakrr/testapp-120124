<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudioTeacher extends Model
{
    protected $table = 'studio_teachers';
    public function teacher(){
      return $this->belongsTo('App\Teacher', 'teacher_id','teacher_id');
  }
}
