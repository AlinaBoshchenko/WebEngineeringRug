<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property-read integer id
 * @property-read string user_name
 * @property-read string carrier_code_rank_1
 * @property-read integer carrier_code_rank_2
 * @property-read integer carrier_code_rank_3
 */
class UserReviews extends Model
{
    protected $table = 'user_reviews';

    protected $guarded = [];

    /**
     * Returns an array representation of this model that can be exposed to the outside world.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'user_name' => $this->user_name,
            'reviews' => $this->reviews,
            'carrier_code_rank_1' => $this->carrier_code_rank_1,
            'carrier_code_rank_2' => $this->carrier_code_rank_2,
            'carrier_code_rank_3' => $this->carrier_code_rank_3,
            'timestamp' => $this['created_at'],
        ];
    }
}
