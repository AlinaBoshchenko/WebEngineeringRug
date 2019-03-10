<?php

namespace App\Http\Controllers;


use function GuzzleHttp\Promise\all;
use Illuminate\Http\Request;

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

    public function getAirports()
    {
        $airports = [];
        $airports[] = [
            'airport_name' => 'John F. Kennedy International Airport',
            'airport_code' => 'JFK',
            'link' => '/airports/JFK'
        ];
        $airports[] = [
            'airport_name' => 'Los Angeles International Airport',
            'airport_code' => 'LAX',
            'link' => '/airports/LAX'
        ];
        $airports[] = [
            'airport_name' => 'Dutch International Airport',
            'airport_code' => 'DIA',
            'link' => '/airports/DIA'
        ];

        $json_airports = [];

        foreach ($airports as $airport) {
            $json_airports[] = json_encode($airport);
        }

        return json_encode($airports);
    }

    public function getAirport($airport_code)
    {
        return json_encode([
            'airport_name' => 'John F. Kennedy International Airport',
            'airport_code' => $airport_code

        ]);
    }


    public function getCarriers()
    {
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

    public function getCarrier($carrier_code)
    {
        return json_encode([
            'carrier_name' => 'name',
            'carrier_code' => $carrier_code
        ]);
    }

    public function getCarriersAtAirport($airport_code)
    {
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
