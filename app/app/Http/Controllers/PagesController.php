<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

/**
 * The controller for all views of the front-end implementation of the API.
 */
class PagesController extends Controller
{

    /**
     * Returns front page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getIntro()
    {
        return view('pages.intro');
    }

    /**
     * Returns page with the list of all the airports
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getAirportsPage()
    {
        return view('pages.airports');
    }

    /**
     * Returns page of the specific airport
     *
     * @param $airport_code
     * @return mixed
     */
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

    /**
     * Returns page with the list of all the carriers
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getCarriersPage()
    {
        return view('pages.carriers');
    }

    /**
     * Returns page of the specific carrier
     *
     * @param $carrier_code
     * @return mixed
     */
    public function getCarrierPage($carrier_code)
    {
        $data = [
            'carrier_code' => $carrier_code
        ];

        return view('pages.carrier')->withData($data);
    }

    /**
     * Returns page with statistics of delays for two airports
     *
     * @param Request $request
     * @return mixed
     */
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

    /**
     * Returns page with form for two airports' statistics
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getStatPage()
    {
        return view('pages.statistics');
    }

    /**
     * Returns page with carrier's statistics form
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getStatCarPage()
    {
        return view('pages.statisticsCar');
    }

    /**
     * Returns page with carrier's statistics
     *
     * @param Request $request
     * @return mixed
     */
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

    /**
     *  Returns page with flight statistics
     *
     * @param Request $request
     * @return mixed
     */
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

    /**
     *  Returns page with flights statistics form
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getStatisticsFlightsPage()
    {
        return view('pages.statisticsFlights');
    }

    /**
     * Returns page with  statistics in minutes form
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getStatisticsMinPage()
    {
        return view('pages.statisticsMin');
    }


    /**
     * Returns page with two options of ranking
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getRankingPage()
    {
        return view('pages.ranking');
    }

    /**
     * Returns page with statistics in minutes
     * @param Request $request
     * @return mixed
     */
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

    /**
     * Returns page with rating by number of delays
     *
     * @param Request $request
     * @return mixed
     */
    public function getRateDelaysPage(Request $request)
    {

        $year = $request['year'];


        $data = [
            'year' => $year
        ];

        return view('pages.rateDelays')->withData($data);
    }

    /**
     * Returns page with rating by cancellations
     *
     * @param Request $request
     * @return mixed
     */
    public function getRateCanPage(Request $request)
    {

        $year = $request['year'];


        $data = [
            'year' => $year
        ];

        return view('pages.rateCan')->withData($data);
    }

    /**
     * Returns page with post review form
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function postReviewPage()
    {

        return view('pages.postReview');
    }

    /**
     * Returns page with external api data
     *
     * @param Request $request
     * @return mixed
     */
    public function getExternalPage(Request $request)
    {

        $location = $request['location'];

        $data = [
            'location' => $location
        ];

        return view('pages.external')->withData($data);
    }

    /**
     * Returns page with search bar for external api
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getExternalIntroPage()
    {
        return view('pages.externalIntro');
    }

    /**
     * Returns page with delete statistics form
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function deleteStatPage()
    {

        return view('pages.deleteStatistics');
    }

    /**
     * Returns page with update statistics form
     *
     * @param $carrier_code
     * @return mixed
     */
    public function updateStatPage($carrier_code)
    {

        $data = [
            'carrier_code' => $carrier_code
        ];
        return view('pages.updateStatistics')->withData($data);
    }

    /**
     * Returns page with post statistics form
     *
     * @param $carrier_code
     * @return mixed
     */
    public function postStatPage($carrier_code)
    {

        $data = [
            'carrier_code' => $carrier_code
        ];
        return view('pages.postStatistics')->withData($data);
    }


    /**
     * Returns page with form to get reviews by user
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getReviewsPage()
    {
        return view('pages.getReviews');
    }

    /**Returns page with form to get review by user and review id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getReviewPage()
    {
        return view('pages.getReview');
    }

    /**
     * Returns page with  reviews by user
     *
     * @param Request $request
     * @return mixed
     */
    public function getByUserPage(Request $request)
    {


        $user_name = $request['user_name'];

        $data = [
            'user_name' => $user_name
        ];

        return view('pages.reviewsByUser')->withData($data);
    }

    /**
     * Returns page with review by user and review id
     *
     * @param Request $request
     * @return mixed
     */
    public function getByIdPage(Request $request)
    {
        $user_name = $request['user_name'];
        $review_id = $request['review_id'];

        $data = [
            'user_name' => $user_name,
            'review_id' => $review_id
        ];

        return view('pages.reviewsById')->withData($data);
    }
}
