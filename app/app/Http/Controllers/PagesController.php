<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

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

    public function getDelaysPage(Request $request)
    {

        $airport1 = $request['airport1'];
        $airport2 = $request['airport2'];

        $data = [
            'airport1' => $airport1,
            'airport2' => $airport2,

        ];

        return view('pages.delays')->withData($data);
    }

    public function getStatPage()
    {
        return view('pages.statistics');
    }

    public function getStatCarPage()
    {
        return view('pages.statisticsCar');
    }


    public function getDelaysCarPage(Request $request)
    {
        $airport1 = $request['airport1'];
        $airport2 = $request['airport2'];
        $carrier_code = $request['carrier_code'];

        $data = [
            'airport1' => $airport1,
            'airport2' => $airport2,
            'carrier_code' => $carrier_code,

        ];

        return view('pages.statForCar')->withData($data);
    }
}
