<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PrivateClassesSpecificDateAvailability extends Model
{
    //
    const CREATED_AT        = 'createdate';
    const UPDATED_AT        = 'updatedate';
    protected $primaryKey   = 'specific_date_availability_id';

    protected $table        = 'private_classes_specific_date_availability';
}
