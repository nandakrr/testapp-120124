<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServiceCategory extends Model
{
    protected $primaryKey = 'service_categories_id';

    public const CREATED_AT = 'category_create_on';
    public const UPDATED_AT = 'category_update_on';


    public function skills() {

//        return $this->belongsToMany('App\Competence', 'service_categories', 'service_categories_id', 'service_categories_id', 'service_category_id', 'service_categories_id');

        return $this->belongsToMany('App\Competence', 'service_categories', 'service_categories_id', 'service_categories_id', 'service_categories_id', 'service_category_id');
    }
}
