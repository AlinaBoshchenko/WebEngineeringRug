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

        $statistics = $this->getStatistics($airport_code, $year, $month);

        if ($statistics === null) {
            return response('Error getting statistics from table', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        if ($statistics === []) {
            return response('no statistics', Response::HTTP_OK);
        }

        $minute_delay_array = [];

        foreach ($statistics as $statistic) {

            $minute_delay = $this->getMiinutesDelayStatistics($statistic['id']);


            if ($minute_delay) {

                $minute_delay_array[] =
                    [
                        'carrier_code' => $statistic['carrier_code'],
                        'carrier_link' => URL::route('api_get_carriers', $statistic['carrier_code']),
                        'airport_code' => $airport_code,
                        'year' => $year,
                        'month' => $month,
                        'late_aircraft' => $minute_delay['late_aircraft'],
                        'carrier' => $minute_delay['carrier'],
                        'total' => $minute_delay['total']
                    ];
            }
        }

        if ($minute_delay_array === []) {
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
     * @return Statistic[]|\Illuminate\Database\Eloquent\Collection|null
     */
    private function getStatistics(string $airport_code, int $year, int $month)
    {

        try {
            $statistics = Statistic::where(['airport_code' => $airport_code, 'month' => $month, 'year' => $year], '=')->get();
        } catch (\Exception $e) {
            return null;
        }

        return $statistics;

    }

    /***
     * @param int $statistics_id
     * @return MinutesDelayedStatistic|\Illuminate\Database\Eloquent\Model|object|null
     */
    private function getMiinutesDelayStatistics(int $statistics_id)
    {

        try {
            $minute_delay = MinutesDelayedStatistic::where('statistics_id', '=', $statistics_id)->first();
            return $minute_delay;
        } catch (\Exception $e) {
            return null;
        }

    }
}