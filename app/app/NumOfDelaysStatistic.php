<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property-read integer statistics_id
 * @property-read integer late_aircraft
 * @property-read integer weather
 * @property-read integer security
 * @property-read integer national_aviation_system
 * @property-read integer carrier
 */
class NumOfDelaysStatistic extends Model
{
    protected $table = 'num_of_delays_statistics';

    /**
     * Returns an array representation of this model that can be exposed to the outside world.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'statistics_id' => $this->statistics_id,
            'late_aircraft' => $this->late_aircraft,
            'weather' => $this->weather,
            'security' => $this->security,
            'national_aviation_system' => $this->national_aviation_system,
            'carrier' => $this->carrier,
        ];
    }
}
