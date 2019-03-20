<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property-read string airport_code
 * @property-read string carrier_code
 */
class CarriersFromAirport extends Model
{
    protected $table = 'carriers_at_airport';
}
