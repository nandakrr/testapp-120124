<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudioLocation extends Model
{
    protected $table = 'studio_location';

    public function studio(){
       return $this->belongsTo('App\Studio', 'studio_id','studio_id');
   }
   public function location(){
      return $this->belongsTo('App\Location', 'location_id','location_id');
  }
}
