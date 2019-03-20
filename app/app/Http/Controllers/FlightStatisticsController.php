<?php

namespace App\Http\Controllers;

use App\FlightStatistic;
use App\Statistic;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class FlightStatisticsController
{

    /***
     * @param Request $request
     * @param $carrier_code
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|Response|\Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function get(Request $request, string $carrier_code)
    {
        $airport_code = $request['airport_code'];
        $year = $request['year'];
        $month = $request['month'];

        $statistics_id = $this->getStatistics($carrier_code, $airport_code, $year, $month)['id'];

        if ($statistics_id === null) {
            return response('Error getting statistics from statistics table', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $flight_statistics = $this->getFlightStatistics($statistics_id);

        if ($flight_statistics === null) {
            return response('Error getting flight statistics from flight_statistics table', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $content_type_requested = $request->header('Content-Type');

        $response_headers = [
            'Content-Type' => $content_type_requested ?? 'application/json',
        ];

        if ($content_type_requested == 'text/csv') {
            $callback = function () use ($flight_statistics) {
                $FH = fopen('php://output', 'w');
                foreach ($flight_statistics as $row) {
                    fputcsv($FH, $row);
                }
                fclose($FH);
            };

            return response()->stream(
                $callback, Response::HTTP_OK, $response_headers
            );
        } elseif ($content_type_requested == 'application/json' || $content_type_requested == null) {
            return response()->json($flight_statistics, Response::HTTP_OK, $response_headers);
        }

        return response('Content-Type given is not supported.', 400);


    }


    /***
     * @param Request $request
     * @param string $carrier_code
     * @return \Illuminate\Contracts\Routing\ResponseFactory|Response|int
     */
    public function post(Request $request, string $carrier_code)
    {

        $airport_code = $request['airport_code'];
        $year = $request['year'];
        $month = $request['month'];

        $statistics_id = $this->getStatistics($carrier_code, $airport_code, $year, $month)['id'];

        if ($statistics_id == null) {
            $statistics_id = $this->createStatistics($carrier_code, $airport_code, $year, $month)['id'];
        }

        if ($this->getFlightStatistics($statistics_id) == null) {

            try {
                FlightStatistic::create([
                    'statistics_id' => $statistics_id,
                    'cancelled' => $request['cancelled'],
                    'on_time' => $request['on_time'],
                    'total' => $request['total'],
                    'delayed' => $request['delayed'],
                    'diverted' => $request['diverted']
                ]);
                return response('Insert succeed', Response::HTTP_OK);
            } catch (\Exception $e) {
                return response('Unable to create a new flightstatistics', Response::HTTP_INTERNAL_SERVER_ERROR);
            }

        } else {
            return response('There is alr flightstatistics exsiting', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }


    /***
     * @param Request $request
     * @param string $carrier_code
     * @return \Illuminate\Contracts\Routing\ResponseFactory|Response
     */
    public function delete(Request $request, string $carrier_code)
    {
        $airport_code = $request['airport_code'];
        $year = $request['year'];
        $month = $request['month'];

        $statistics_id = $this->getStatistics($carrier_code, $airport_code, $year, $month)['id'];

        if ($statistics_id == null) {
            return response('There is nothing to delete', Response::HTTP_INTERNAL_SERVER_ERROR);
        }


        $flight_statistics = $this->getFlightStatistics($statistics_id);

        if ($flight_statistics == null) {
            return response('There is nothing to delete', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        try {
            FlightStatistic::where('statistics_id', '=', $statistics_id)->delete();
            return response('Delete succeed', Response::HTTP_OK);
        } catch (\Exception $e) {
            return response('Unable to delete flightstatistics', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }


    /***
     * @param Request $request
     * @param string $carrier_code
     * @return \Illuminate\Contracts\Routing\ResponseFactory|Response
     */
    public function put(Request $request, string $carrier_code)
    {
        $airport_code = $request['airport_code'];
        $year = $request['year'];
        $month = $request['month'];

        $statistics_id = $this->getStatistics($carrier_code, $airport_code, $year, $month)['id'];

        if ($statistics_id == null) {
            return response('There is nothing to update', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $flight_statistics = $this->getFlightStatistics($statistics_id);

        if ($flight_statistics == null) {
            return response('There is nothing to update', Response::HTTP_INTERNAL_SERVER_ERROR);
        }


        try {
            if ($request['cancelled']) {
                FlightStatistic::where('statistics_id', '=', $statistics_id)->update(['cancelled' => $request['cancelled']]);
            }


            if ($request['on_time']) {
                FlightStatistic::where('statistics_id', '=', $statistics_id)->update(['on_time' => $request['on_time']]);
            }

            if ($request['total']) {
                FlightStatistic::where('statistics_id', '=', $statistics_id)->update(['total' => $request['total']]);
            }

            if ($request['delayed']) {
                FlightStatistic::where('statistics_id', '=', $statistics_id)->update(['delayed' => $request['delayed']]);
            }

            if ($request['diverted']) {
                FlightStatistic::where('statistics_id', '=', $statistics_id)->update(['diverted' => $request['diverted']]);
            }
            return response('Update succeed', Response::HTTP_OK);

        } catch (\Exception $e) {

            return response('Unable to update', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }


    /**
     * @param string $carrier_code
     * @param string $airport_code
     * @param int $year
     * @param int $month
     * @return Statistic|\Illuminate\Database\Eloquent\Model|object|null
     */
    private function getStatistics(string $carrier_code, string $airport_code, int $year, int $month)
    {

        try {
            $statistics = Statistic::where(['carrier_code' => $carrier_code, 'airport_code' => $airport_code, 'month' => $month, 'year' => $year], '=')->first();
            return $statistics;
        } catch (\Exception $e) {
            return null;
        }

    }


    /***
     * @param string $carrier_code
     * @param string $airport_code
     * @param int $year
     * @param int $month
     * @return Statistic|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Database\Eloquent\Model|Response
     */
    private function createStatistics(string $carrier_code, string $airport_code, int $year, int $month)
    {

        try {
            $statistics = Statistic::create([
                'carrier_code' => $carrier_code,
                'airport_code' => $airport_code,
                'month' => $month,
                'year' => $year
            ]);
            return $statistics;
        } catch (\Exception $e) {
            return response('unable to create statistics', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /***
     * @param int $statistics_id
     * @return FlightStatistic|\Illuminate\Database\Eloquent\Model|object|null
     */
    private function getFlightStatistics(int $statistics_id)
    {
        try {
            $flight_statistics = FlightStatistic::where('statistics_id', '=', $statistics_id)->first();
            return $flight_statistics;
        } catch (\Exception $e) {
            return null;
        }
    }

}