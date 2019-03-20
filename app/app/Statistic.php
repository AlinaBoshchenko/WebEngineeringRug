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
}
