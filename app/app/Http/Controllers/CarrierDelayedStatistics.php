<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class CarrierDelayedStatistics extends Controller
{
    public function get(Request $request)
    {
        $airport_1 = Input::get('airport_1');
        $airport_2 = Input::get('airport_2');


    }
}
