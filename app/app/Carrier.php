<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property-read string carrier_code
 * @property-read string carrier_name
 */
class Carrier extends Model
{
    protected $table = 'carriers';
}
