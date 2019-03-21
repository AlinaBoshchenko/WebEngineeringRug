<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property-read integer id
 * @property-read string airport_code
 * @property-read string carrier_code
 * @property-read integer month
 * @property-read integer year
 */
class Statistic extends Model
{
    protected $table = 'statistics';

    protected $guarded = [];

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
            'month' => $this->month,
            'year' => $this->year,
        ];
    }
}
