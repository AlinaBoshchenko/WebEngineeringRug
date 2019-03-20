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

    /**
     * Returns an array representation of this model that can be exposed to the outside world.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'airport_name' => $this->airport_name,
            'airport_code' => $this->airport_code,
        ];
    }
}
