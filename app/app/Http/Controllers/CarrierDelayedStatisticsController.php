<?php

namespace App\Http\Controllers;

use App\MinutesDelayedStatistic;
use App\Statistic;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Input;

/**
 * Handles requests for the 'carriers/statistics/delays' API endpoint.
 */
class CarrierDelayedStatisticsController extends Controller
{
    /**
     * @param Request $request
     * @param string|null    $carrier_code
     *
     * @return Response
     */
    public function get(Request $request, $carrier_code = null)
    {
        $airport_code_1 = Input::get('airport1') ?? null;
        $airport_code_2 = Input::get('airport2') ?? null;

        if ($airport_code_1 === null || $airport_code_2 === null) {
            return response('Airport codes not properly given', Response::HTTP_BAD_REQUEST);
        }

        try {
            $statistic_ids = $this->getStatisticIDsForCommonCarrierAirports($airport_code_1, $airport_code_2, $carrier_code);
        } catch (\Exception $e) {
            return response('Could not find or load statistics for given airport codes.', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $total_minutes_delayed_per_month = [];
        foreach ($statistic_ids as $statistic_id) {
            $minutes_delayed_statistics = MinutesDelayedStatistic::where('statistics_id', '=', $statistic_id)->first();

            if ($minutes_delayed_statistics == null) {
                continue;
            }

            //Each row in the statistics table is for one month.
            $total_minutes_delayed_per_month[] = $minutes_delayed_statistics->late_aircraft + $minutes_delayed_statistics->carrier;
        }

        sort($total_minutes_delayed_per_month); //Sort in ascending order

        $stat_size = \count($total_minutes_delayed_per_month);
        $mean = array_sum($total_minutes_delayed_per_month) / $stat_size;
        $median = $total_minutes_delayed_per_month[(int) ($stat_size / 2)];
        $std = $this->std_deviation($total_minutes_delayed_per_month);

        $airport_1_as_array = (new AirportsController())->getAirportAsArray($airport_code_1, true);
        $airport_2_as_array = (new AirportsController())->getAirportAsArray($airport_code_2, true);
        $carrier_as_array = $carrier_code ? (new CarriersController())->getCarrierAsArray($carrier_code, true) : null;

        if (empty($airport_1_as_array) || empty($airport_2_as_array || $carrier_as_array)) {
            return response('Airport or carrier not found.', Response::HTTP_OK);
        }

        $content_body = \array_filter([
            'carrier' => $carrier_as_array,
            'airport1' => $airport_1_as_array,
            'airport2' => $airport_2_as_array,
            'mean' => round($mean, 4),
            'median' => round($median, 4),
            'standard_deviation' => round($std, 4),
        ]);

        $content_type_requested = $request->header('Accept');

        $response_headers = [
            'Content-Type' => $content_type_requested == 'text/csv' ? $content_type_requested : 'application/json',
        ];

        if ($content_type_requested == 'text/csv') {
            $callback = function () use ($content_body) {
                $FH = fopen('php://output', 'w');
                $string = [
                    'mean' => $content_body['mean'],
                    'median' => $content_body['median'],
                    'standard_deviation' => $content_body['standard_deviation'],
                ];

                foreach ($content_body['airport1'] as $key => $airport1){
                    $string['airport1_' . $key] = $airport1;
                }
                foreach ($content_body['airport2'] as $key => $airport2){
                    $string['airport1_' . $key] = $airport2;
                }

                if ($content_body['carrier']) {
                    foreach ($content_body['carrier'] as $key => $carrier){
                        $string['carrier_' . $key] = $carrier;
                    }
                }

                fputcsv($FH, \array_keys($string));
                fputcsv($FH, $string);
                fclose($FH);
            };

            return response()->stream($callback, Response::HTTP_OK, $response_headers);
        } else {
            return response()->json($content_body, Response::HTTP_OK, $response_headers);
        }
    }

    /**
     * Calculate the standard deviation of an array.
     *
     * @param array $arr
     *
     * @return float
     */
    function std_deviation(array $arr)
    {
        $num_of_elements = count($arr);

        $variance = 0.0;

        // calculating mean using array_sum() method
        $average = array_sum($arr)/$num_of_elements;

        foreach($arr as $i) {
            $variance += pow(($i - $average), 2);
        }

        return (float)sqrt($variance/$num_of_elements);
    }

    /**
     * For two given airport_codes, get the statistic IDs when they have common carriers.
     *
     * @param string      $airport_code_1
     * @param string      $airport_code_2
     * @param string|null $carrier_code [Optional, default = null]
     *
     * @return int[]
     */
    public function getStatisticIDsForCommonCarrierAirports(
        string $airport_code_1,
        string $airport_code_2,
        string $carrier_code = null
    ): array
    {
        if ($carrier_code === null) {
            $statistics_for_airport_1 = Statistic::where('airport_code', '=', $airport_code_1)->get();
        } else {
            $statistics_for_airport_1 = Statistic::where(
                [
                    'airport_code' => $airport_code_1,
                    'carrier_code' => $carrier_code
                ],
                '='
            )->get();
        }

        $carriers = []; //All unique carriers serving airport_code_1
        foreach ($statistics_for_airport_1 as $statistic) {
            if (!\in_array($statistic->carrier_code, $carriers)) {
                $carriers[] = $statistic->carrier_code;
            };
        }

        //Retrieve statistics only for the ones that have the same carriers as airport_1
        $statistics_for_airport_2 = Statistic::where(
            [
                'airport_code' => $airport_code_2,
                'carrier_code' => $carriers
            ],
            [
                '=',
                'IN'
            ]
        )->get();

        $stat_ids = [];
        foreach ($statistics_for_airport_1 as $statistic) {
            $stat_ids[] = $statistic->id;
        }

        foreach ($statistics_for_airport_2 as $statistic) {
            $stat_ids[] = $statistic->id;
        }

        return $stat_ids;
    }
}
