<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentClass extends Model
{
    protected $table = 'student_classes';

    public function student(){
       return $this->belongsTo('App\Student', 'student_id','student_id');
   }
}
