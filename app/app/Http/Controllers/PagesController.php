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

    public function getFlightsStatPage(Request $request)
    {

        $carrier_code = $request['carrier_code'];
        $route = $request['route'];
        $month = $request['month'];
        $year = $request['year'];
        $airport_code = $request['airport_code'];

        $data = [

            'carrier_code' => $carrier_code,
            'route' => $route,
            'month' => $month,
            'year' => $year,
            'airport_code' => $airport_code
        ];

        return view('pages.flightStat')->withData($data);
    }

    public function getStatisticsFlightsPage()
    {
        return view('pages.statisticsFlights');
    }

    public function getStatisticsMinPage()
    {
        return view('pages.statisticsMin');
    }

    public function getRankingPage()
    {
        return view('pages.ranking');
    }

    public function getStatMinPage(Request $request)
    {

        $airport_code = $request['airport_code'];
        $month = $request['month'];
        $year = $request['year'];
        $reasons = $request['reasons'];

        $data = [

            'airport_code' => $airport_code,
            'month' => $month,
            'year' => $year,
            'reasons' => $reasons
        ];

        return view('pages.statMin')->withData($data);
    }

    public function getRateDelaysPage(Request $request)
    {

        $year = $request['year'];


        $data = [
            'year' => $year
        ];

        return view('pages.rateDelays')->withData($data);
    }

    public function getRateCanPage(Request $request)
    {

        $year = $request['year'];


        $data = [
            'year' => $year
        ];

        return view('pages.rateCan')->withData($data);
    }

    public function postReviewPage()
    {

        return view('pages.postReview');
    }

    public function getExternalPage(Request $request)
    {

        $location = $request['location'];

        $data = [
            'location' => $location
        ];

        return view('pages.external')->withData($data);
    }

    public function getExternalIntroPage()
    {
        return view('pages.externalIntro');
    }

    public function deleteStatPage()
    {

        return view('pages.deleteStatistics');
    }

    public function updateStatPage($carrier_code)
    {

        $data = [
            'carrier_code' => $carrier_code
        ];
        return view('pages.updateStatistics')->withData($data);
    }

    public function postStatPage($carrier_code)
    {

        $data = [
            'carrier_code' => $carrier_code
        ];
        return view('pages.postStatistics')->withData($data);
    }
}
