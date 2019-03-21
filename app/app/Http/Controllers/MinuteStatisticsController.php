<?php

namespace App\Http\Controllers;

use App\MinutesDelayedStatistic;
use App\Statistic;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Handles requests for the 'carriers/statistics/minutes_delayed' API endpoint.
 */
class MinuteStatisticsController
{
    /***
     * @param Request $request
     *
     * @return Response|StreamedResponse
     */
    public function get(Request $request)
    {
        $airport_code = $request['airport_code'];
        $year = $request['year'];
        $month = $request['month'];
        $reasons = $request['reasons'];

        if($reasons == null){
            return response('No reason array is given by the user', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        if(!$this->isCorrectInputDate($request)){
            return response('Bad date given by the user', Response::HTTP_BAD_REQUEST);
        }

        if($airport_code === null){
            $statistics = $this->getStatisticsWhenAirportNotGiven($request);
        } else {
            if(!\is_string($airport_code)){
                return response('Bad syntax', Response::HTTP_BAD_REQUEST);
            }else{
                $statistics = $this->getStatisticsWhenAirportIsGiven($request);
            }
        }

        if ($statistics === null) {
            return response('Error getting statistics from table', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        if (empty($statistics)) {
            return response('No statistics', Response::HTTP_OK);
        }

        $minute_delay_array = [];
        foreach ($statistics as $statistic) {
            $minutes_delayed_stats = $this->getMinutesDelayStatistics($statistic['id']);

            if ($minutes_delayed_stats) {
                $minute_delay_array[] =
                    [
                        'carrier' => [
                            'carrier_code' => $statistic['carrier_code'],
                            'carrier_link' => URL::route('api_get_carriers', $statistic['carrier_code'])
                        ],
                        'airport_code' => $airport_code,
                        'year' => $year,
                        'month' => $month,
                        'reasons' => \array_filter(
                            [
                                'late_aircraft' => \in_array('late_aircraft', $reasons) ? $minutes_delayed_stats['late_aircraft'] : null,
                                'carrier' => \in_array('carrier', $reasons) ? $minutes_delayed_stats['carrier'] : null,
                                'total' => \in_array('total', $reasons) ? $minutes_delayed_stats['total'] : null
                            ]
                        )
                    ];
            }
        }

        if (empty($minute_delay_array)) {
            return response('No corresponding statistics', Response::HTTP_OK);
        }

        $content_type_requested = $request->header('Content-Type');

        $response_headers = [
            'Content-Type' => $content_type_requested ?? 'application/json',
        ];

        if ($content_type_requested == 'text/csv') {
            $callback = function () use ($minute_delay_array) {
                $FH = fopen('php://output', 'w');
                foreach ($minute_delay_array as $row) {
                    fputcsv($FH, $row);
                }
                fclose($FH);
            };

            return response()->stream(
                $callback, Response::HTTP_OK, $response_headers
            );
        } elseif ($content_type_requested == 'application/json' || $content_type_requested == null) {
            return response()->json($minute_delay_array, Response::HTTP_OK, $response_headers);
        }

        return response('Content-Type given is not supported.', 400);
    }

    /***
     * @param string $airport_code
     * @param int $year
     * @param int $month
     *
     * @return Statistic[]|null
     */
    private function getStatisticsForGivenAirportYearMonth(string $airport_code, int $year, int $month)
    {
        try {
            return Statistic::where(['airport_code' => $airport_code, 'month' => $month, 'year' => $year], '=')->get();
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * @param int $year
     * @param int $month
     *
     * @return Statistic[]|null
     */
    private function getStatisticsForGivenYearMonth(int $year, int $month)
    {
        try {
            return Statistic::where(['month' => $month, 'year' => $year], '=')->get();
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * @param int $year
     *
     * @return Statistic[]|null
     */
    private function getStatisticsForGiveYear(int $year)
    {
        try {
            return Statistic::where(['year' => $year], '=')->get();
        } catch (\Exception $e) {
            return null;
        }

    }

    /**
     * @param int $month
     *
     * @return Statistic[]|null
     */
    private function getStatisticsForGivenMonth(int $month)
    {
        try {
            return Statistic::where(['month' => $month], '=')->get();
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * @param string $airport_code
     * @param int $year
     *
     * @return Statistic[]|null
     */
    private function getStatisticsForGivenAirportYear(string $airport_code, int $year)
    {
        try {
            return Statistic::where(['airport_code' => $airport_code, 'year' => $year], '=')->get();
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * @param string $airport_code
     * @param int $month
     *
     * @return Statistic[]|null
     */
    private function getStatisticsForGivenAirportMonth(string $airport_code, int $month)
    {
        try {
            return Statistic::where(['airport_code' => $airport_code, 'month' => $month], '=')->get();
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * @param string $airport_code
     *
     * @return Statistic[]|null
     */
    private function getStatisticsForGivenAirport(string $airport_code)
    {
        try {
            return Statistic::where(['airport_code' => $airport_code], '=')->get();
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * @param Request $request
     *
     * @return Statistic[]|null
     */
    private function getStatisticsWhenAirportNotGiven(Request $request)
    {
        $year = $request['year'] ?? null;
        $month = $request['month'] ?? null;

        if($year === null && $month === null){
            try {
                return Statistic::all();
            } catch (\Exception $e){
                return null;
            }
        } else if($year === null){
            return $this->getStatisticsForGivenMonth($month);
        } else if ($month === null) {
            return $this->getStatisticsForGiveYear($year);
        } else {
            return $this->getStatisticsForGivenYearMonth($year, $month);
        }
    }

    /**
     * @param Request $request
     *
     * @return Statistic[]|null
     */
    private function getStatisticsWhenAirportIsGiven(Request $request)
    {
        $airport_code = $request['airport_code'] ?? null;
        $year = $request['year'] ?? null;
        $month = $request['month'] ?? null;

        if ($year === null && $month === null) {
            return $this->getStatisticsForGivenAirport($airport_code);
        } else if ($year === null) {
            return $this->getStatisticsForGivenAirportMonth($airport_code, $month);
        } else if ($month === null) {
            return $this->getStatisticsForGivenAirportYear($airport_code, $year);
        } else {
            return $this->getStatisticsForGivenAirportYearMonth($airport_code, $year, $month);
        }
    }

    /***
     * @param int $statistics_id
     *
     * @return MinutesDelayedStatistic|null
     */
    private function getMinutesDelayStatistics(int $statistics_id)
    {
        try {
            return MinutesDelayedStatistic::where('statistics_id', '=', $statistics_id)->first();
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * @param Request $request
     *
     * @return bool
     */
    private function isCorrectInputDate(Request $request)
    {
        $year = $request['year'] ?? null;
        $month = $request['month'] ?? null;

        if($year == null && $month == null){
            return true;
        } else if($year == null){
            return !(!\is_numeric($month) || $month < 1 || $month > 12);
        } else if($month == null){
            return !(!\is_numeric($year) || $year < 1000 || $year > date('Y')) ;
        } else{
            return !(
                !\is_numeric($year) ||
                !\is_numeric($month) ||
                !(new FlightStatisticsController())->sanitizeDate($month, $year)
            );
        }
    }
}
