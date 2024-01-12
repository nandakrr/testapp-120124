<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TeacherSkill extends Model
{
    public $timestamps = false;
    protected $dates = ['created_at'];
    protected $primaryKey = 'teacher_skills_id';

    public function skill() {

        return $this->hasOne('App\Competence', 'skill_id', 'skill_id');
    }
}
