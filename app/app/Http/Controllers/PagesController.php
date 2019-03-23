<?php

namespace App\Http\Controllers;

/**
 * The controller for all views of the front-end implementation of the API.
 */
class PagesController extends Controller
{
    public function getIndex()
    {
        return view ('pages.welcome');
    }

    public function getIntro()
    {
        return view('pages.intro');
    }

    public function getAirportsPage()
    {
        return view('pages.airports');
    }

    public function getAirportPage($airport_code)
    {
        $data = [
            'airport_code' => $airport_code
        ];
        $data2 = [
            'airport_code' => $airport_code
        ];

        return view('pages.airport')->withData($data, $data2);
    }

    public function getCarriersPage()
    {
        return view('pages.carriers');
    }


    public function getCarrierPage($carrier_code)
    {
        $data = [
            'carrier_code' => $carrier_code
        ];

        return view('pages.carrier')->withData($data);
    }
}
