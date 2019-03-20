<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property-read integer statistics_id
 * @property-read integer cancelled
 * @property-read integer on_time
 * @property-read integer total
 * @property-read integer delayed
 *@property-read integer diverted
 */
class FlightStatistic extends Model
{
    protected $table = 'flight_statistics';

    protected $guarded = [];
}
