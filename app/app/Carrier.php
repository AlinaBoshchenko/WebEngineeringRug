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

    /**
     * Returns an array representation of this model that can be exposed to the outside world.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'carrier_name' => $this->carrier_name,
            'carrier_code' => $this->carrier_code,
        ];
    }
}
