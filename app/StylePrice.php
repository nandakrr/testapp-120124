<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StylePrice extends Model
{
    protected $table = 'style_price';
    public $timestamps = false;
    protected $fillable = [
        'style_id', 'location_id', 'default_price_credit','default_price_dollar','created_date',
    ];
}
