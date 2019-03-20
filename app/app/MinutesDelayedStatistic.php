<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property-read integer statistics_id
 * @property-read integer late_aircraft
 * @property-read integer weather
 * @property-read integer carrier
 * @property-read integer security
 * @property-read integer total
 * @property-read integer national_aviation_system
 */
class MinutesDelayedStatistic extends Model
{
    protected $table = 'minutes_delayed_statistics';
}
