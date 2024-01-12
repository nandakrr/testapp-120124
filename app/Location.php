<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $table = 'location';
    protected $primaryKey = 'location_id';
    
    public function avaibilities()
    {
        return $this->hasMany('App\PrivateClassesWeekdayAvailability', 'location_id', 'location_id');
    }
}
