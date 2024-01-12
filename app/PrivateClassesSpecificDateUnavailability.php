<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PrivateClassesSpecificDateUnavailability extends Model
{
    //
    const CREATED_AT        = 'create_at';
    const UPDATED_AT        = 'update_on';
    protected $primaryKey   = 'specific_date_unavailability_id';

    protected $table        = 'private_classes_specific_date_unavailability';
}
