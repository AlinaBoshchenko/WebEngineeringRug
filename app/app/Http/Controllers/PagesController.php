<?php

namespace App\Http\Controllers;

use App\Airport;
use App\Carrier;
use App\FlightStatistic;
use App\Statistic;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

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

    public function getAirport($airport_code)
    {
    }

    public function getCarriers()
    {
        $carriers = Carrier::all();

        $json_carriers = [];
        foreach ($carriers->toArray() as $carrier) {
            $json_carriers[] =
                [
                    'carrier_name' => $carrier['carrier_name'],
                    'carrier_code' => $carrier['carrier_code'],
                    'link' => '/carrier/' . $carrier['carrier_code']
                ];
        }

        return $json_carriers;
    }

    public function getCarrier($carrier_code)
    {
        $carrier = Carrier::where('carrier_code' , '=' , $carrier_code)->first();
        $carrier_as_array = $carrier->toArray();

        return  [
            'carrier_code' => $carrier_as_array['carrier_code'],
            'carrier_name' => $carrier_as_array['carrier_name'],
        ];
    }

    public function getCarriersAtAirport($airport_code)
    {
        //TODO not done. need one migration
        $carriers = [];
        $carriers[] = [
            'carrier_name' => 'name1',
            'carrier_code' => 'code1',
            'link' => 'link1'
        ];
        $carriers[] = [
            'carrier_name' => 'name2',
            'carrier_code' => 'code2',
            'link' => 'link2'
        ];

        $json_carriers = [];

        foreach ($carriers as $carrier) {
            $json_carriers[] = json_encode($carrier);
        }

        return json_encode($carriers);
    }

    public function getCarrierStatistics($carrier_code, Request $request)
    {
        $statistics = Statistic::where(['carrier_code' => $carrier_code, 'month' => $request['month'], 'year' => $request['year']] , '=')->get();
        $statistics_as_array = $statistics->toArray();

        $statistic_ids = array_map(
            function ($statistic_as_array) {
                return $statistic_as_array['id'];
            }, $statistics_as_array
        );

        $flight_statistics = FlightStatistic::whereIn('statistics_id', $statistic_ids)->get();
        $flight_statistics_as_array = $flight_statistics->toArray();

//        $stats = [];
//        foreach ($flight_statistics_as_array as $flight_statistic) {
//            $stats[] = [
//                'cancelled' => 1,
//                'on_time' => 2,
//                'total' => 3,
//                'delayed' => 4,
//                'diverted' => 5
//            ];
//        }

        return [
            'route' => $request['route'],
            'month' => $request['month'],
            'year' => $request['year'],
            'statistics' => $flight_statistics_as_array
        ];

    }

    public function postCarrierStatistics($carrier_code, Request $request)
    {
        return json_encode([
            'route' => $request['route'],
            'month' => $request['month'],
            'year' => $request['year'],
            'statistics' => [
                'cancelled' => 1,
                'on time' => 2,
                'total' => 3,
                'delayed' => 4,
                'diverted' => 5
            ]

        ]);
    }

    public function deleteCarrierStatistics($carrier_code, Request $request)
    {
        return 1;
    }

    public function putCarrierStatistics($carrier_code, Request $request)
    {
        return 1;
    }

    public function getCarriersStatisticsMinutes(Request $request)
    {
        $carriers = [];
        $carriers[] = [
            'carrier_name' => 'name1',
            'carrier_code' => 'code1',
            'link' => 'link1',
            'airport_Code' => $request['airport_code'],
            'month' => $request['month'],
            'year' => $request['year'],
            'reasons' => [
                'late aircraft' => 2,
                'carrier' => 2,
                'total' => 2
            ]
        ];
        $carriers[] = [
            'carrier_name' => 'name2',
            'carrier_code' => 'code2',
            'link' => 'link1',
            'airport_Code' => $request['airport_code'],
            'month' => $request['month'],
            'year' => $request['year'],
            'reasons' => [
                'late aircraft' => 2,
                'carrier' => 2,
                'total' => 2
            ]
        ];

        $json_carriers = [];

        foreach ($carriers as $carrier) {
            $json_carriers[] = json_encode($carrier);
        }

        return json_encode($carriers);
    }

    public function getCarriersStatisticsDelays(Request $request)
    {
        return json_encode([
            'carrier' => [
                'carrier_name' => 'name1',
                'carrier_code' => 'code1',
                'link' => 'link'
            ],
            'airport1' => [
                'air_name' => 'name',
                'air_code' => 'code',
                'link' => 'link'
            ],
            'airport2' => [
                'air_name' => 'name',
                'air_code' => 'code',
                'link' => 'link'
            ],
            'mean' => 1,
            'median' => 1,
            'stdv' => 1
        ]);
    }

    public function getCarrierStatisticsDelays(Request $request)
    {
        return json_encode([
            'carrier' => [
                'carrier_name' => 'name1',
                'carrier_code' => 'code1',
                'link' => 'link'
            ],
            'airport1' => [
                'air_name' => 'name',
                'air_code' => 'code',
                'link' => 'link'
            ],
            'airport2' => [
                'air_name' => 'name',
                'air_code' => 'code',
                'link' => 'link'
            ],
            'mean' => 1,
            'median' => 1,
            'stdv' => 1
        ]);
    }
}
