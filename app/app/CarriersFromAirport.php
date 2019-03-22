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

    /**
     * Returns an array representation of this model that can be exposed to the outside world.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'airport_code' => $this->airport_code,
            'carrier_code' => $this->carrier_code,
        ];
    }
}
