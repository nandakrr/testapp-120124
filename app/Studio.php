<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Studio extends Model
{
    // use Notifiable;

    protected $table = 'studio';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','token',
    ];

    
    public function locations()
    {
        return $this->hasMany('App\StudioLocation', 'location_id', 'studio_id');

        // return $this->hasManyThrough(
        //     'App\Location',
        //     'App\StudioLocation',
        //     'studios_id', // Foreign key on users table...
        //     'location_id', // Foreign key on posts table...
        //     'studio_id', // Local key on countries table...
        //     'location_id' // Local key on users table...
        // ); 
    }
}
