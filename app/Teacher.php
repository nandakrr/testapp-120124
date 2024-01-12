<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Teacher extends Authenticatable
{
    protected $table = 'teacher';
    protected $fillable = [
        'firstname', 'email', 'lastname', 'password', 'role'
    ];
    protected $primaryKey = 'teacher_id';

    public function getFullNameAttribute()
    {
        return ucfirst($this->firstname) . ' ' . ucfirst($this->lastname);
    }

    public function avaibilities()
    {
        return $this->hasMany('App\PrivateClassesWeekdayAvailability', 'teacher_id', 'teacher_id');
    }

    public function specialHoursAvaibilities()
    {
        return $this->hasMany('App\PrivateClassesSpecificDateAvailability', 'teacher_id', 'teacher_id');
    }

    public function specialHoursUnavaibilities()
    {
        return $this->hasMany('App\PrivateClassesSpecificDateUnavailability', 'teacher_id', 'teacher_id');
    }

    public function competencies()
    {

        return $this->hasManyThrough(
            'App\Competence',
            'App\TeacherSkill',
            'teacher_id', // Foreign key on users table...
            'skill_id', // Foreign key on posts table...
            'teacher_id', // Local key on countries table...
            'skill_id' // Local key on users table...
        );
    }


    public function studios()
    {

        return $this->hasManyThrough(
            'App\Studio',
            'App\StudioTeacher',
            'teacher_id', // Foreign key on users table...
            'admin_id', // Foreign key on posts table...
            'teacher_id', // Local key on countries table...
            'studio_id' // Local key on users table...
        );
    }


    public function images()
    {
        return $this->hasMany('App\TeacherImage', 'teacher_id', 'teacher_id');
    }


    /**
     *
     */
    public function weeklyAvailabilities() {

        return $this->hasMany('App\TeacherOnDemandWeeklyAvailability', 'teacher_id', 'teacher_id');
    }



    public function specificDateAvailabilities()
    {
        return $this->hasMany('App\TeacherOnDemandSpecificDateAvailability', 'teacher_id', 'teacher_id');
    }


    public function specificDateUnavailabilities()
    {
        return $this->hasMany('App\TeacherOnDemandSpecificDateUnavailability', 'teacher_id', 'teacher_id');
    }


    public function education()
    {
        return $this->hasOne('App\TeacherEducation', 'teacher_id');
    }


    public function degrees()
    {
        return $this->hasMany('App\TeacherEducation', 'teacher_id', 'teacher_id');
    }


    public function skills()
    {
        return $this->hasMany('App\TeacherSkill', 'teacher_id', 'teacher_id');
    }
}
