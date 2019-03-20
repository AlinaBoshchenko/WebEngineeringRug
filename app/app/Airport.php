<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property-read string airport_code
 * @property-read string airport_name
 */
class Airport extends Model
{
    protected $table = 'airports';
}
