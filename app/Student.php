<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Student extends Authenticatable
{
      protected $table = 'student';

      public function getFullNameAttribute() {
        return ucfirst($this->firstname) . ' ' . ucfirst($this->lastname);
    }
}
