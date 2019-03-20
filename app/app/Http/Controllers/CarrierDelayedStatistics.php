<?php

namespace App\Http\Controllers;

use App\MinutesDelayedStatistic;
use App\Statistic;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Input;

/**
 *
 */
class CarrierDelayedStatistics extends Controller
{
    public function get(Request $request)
    {
        $airport_code_1 = Input::get('airport_1');
        $airport_code_2 = Input::get('airport_2');

        if ($airport_code_1 == null || $airport_code_2 == null) {
            return response('Invalid query!', Response::HTTP_BAD_REQUEST);
        }

        try {
            $statistic_ids = $this->getStatisticIDsForCommonCarrierAirports($airport_code_1, $airport_code_2);
        } catch (\Exception $e) {
            return response('Could not find or load statistics for given airport codes.', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $total_minutes_delayed_per_month = [];
        foreach ($statistic_ids as $id) {
            $minutes_delayed_statistics = MinutesDelayedStatistic::where('statistics_id', '=', $id)->first();
            //Each row in the statistics table is for one month.
            $total_minutes_delayed_per_month[] = $minutes_delayed_statistics->late_aircraft + $minutes_delayed_statistics->carrier;
        }

        sort($total_minutes_delayed_per_month); //Ascending order

        $stat_size = \count($total_minutes_delayed_per_month);
        $mean = array_sum($total_minutes_delayed_per_month) / $stat_size;
        $median = $total_minutes_delayed_per_month[(int) ($stat_size / 2)];
        $std = $this->std_deviation($total_minutes_delayed_per_month);

        $airport_1_as_array = (new AirportsController())->getAirportAsArray($airport_code_1);
        $airport_2_as_array = (new AirportsController())->getAirportAsArray($airport_code_2);

        return [
            'airport_1' => $airport_1_as_array,
            'airport_2' => $airport_2_as_array,
            'mean' => round($mean, 4),
            'median' => round($median, 4),
            'standard_deviation' => round($std, 4),
        ];
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
     * @param string $airport_code_1
     * @param string $airport_code_2
     *
     * @throws \Exception When a problem with reading from the database occurs.
     *
     * @return int[]
     */
    public function getStatisticIDsForCommonCarrierAirports(string $airport_code_1, string $airport_code_2): array
    {
        $statistics_for_airport_1 = Statistic::where('airport_code', '=', $airport_code_1)->get();

        $carriers = [];
        foreach ($statistics_for_airport_1 as $statistic) {
            if (!\in_array($statistic->carrier_code, $carriers)) {
                $carriers[] = $statistic->carrier_code;
            };
        }
        //Retrieve only the ones that have the same carriers as in statistics_for_airport_1
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
