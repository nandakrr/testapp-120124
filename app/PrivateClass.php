<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PrivateClass extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
    
    protected $table = 'private_classes';


    public function teacher() {

        return $this->hasOne('App\Teacher', 'teacher_id', 'teacher_id');
    }


    public function student() {

        return $this->hasOne('App\Teacher', 'student_id', 'student_id');
    }
}
