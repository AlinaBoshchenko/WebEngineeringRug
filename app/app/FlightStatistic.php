<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property-read integer statistics_id
 * @property-read integer cancelled
 * @property-read integer on_time
 * @property-read integer total
 * @property-read integer delayed
 * @property-read integer diverted
 */
class FlightStatistic extends Model
{
    protected $table = 'flight_statistics';

    protected $guarded = [];

    /**
     * Returns an array representation of this model that can be exposed to the outside world.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'statistics_id' => $this->statistics_id,
            'cancelled' => $this->cancelled,
            'on_time' => $this->on_time,
            'total' => $this->total,
            'delayed' => $this->delayed,
            'diverted' => $this->diverted,
        ];
    }
}
