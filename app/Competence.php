<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Competence extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
    
    protected $table = 'competencies';

    protected $primaryKey = 'skill_id';


    public function service() {

        return $this->belongsTo('App\ServiceCategory', 'service_category_id', 'service_categories_id');
    }
}
