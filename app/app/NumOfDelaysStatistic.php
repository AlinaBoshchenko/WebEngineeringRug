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
}
