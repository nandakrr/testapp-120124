<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PrivateClassesWeekdayAvailability extends Model
{
    //
    const CREATED_AT = 'create_at';
    const UPDATED_AT = 'update_on';

    protected $table = 'private_classes_weekday_availability';
}
