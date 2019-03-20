<?php

namespace App\Http\Controllers;

use App\FlightStatistic;
use App\Statistic;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\StreamedResponse;


class FlightStatisticsController
{

    /**
     * @param Request $request
     * @param $carrier_code
     * @return Response|StreamedResponse
     */
    public function get(Request $request, string $carrier_code)
    {
        $airport_code = $request['airport_code'];
        $year = $request['year'];
        $month = $request['month'];
        $route = $request['route'];
        $filter = $request['filter'];

        if ($route == null) {
            return response('Route is not given by user', Response::HTTP_BAD_REQUEST);
        }

        if ($filter == null) {
            $filter = [];
        }

        if ($year == null && $month == null) {
            $statistics_collection = $this->getStatisticsForAll($carrier_code, $airport_code);
        } else if ($year == null) {
            if (!\is_numeric($month) || $month < 1 || $month > 12) {
                return response('Bad syntax', Response::HTTP_BAD_REQUEST);
            } else {
                $statistics_collection = $this->getStatisticsForAMonth($carrier_code, $airport_code, $month);
            }
        } else if ($month == null) {
            if (!\is_numeric($year) || $year < 1000 || $year > date('Y')) {
                return response('Bad syntax', Response::HTTP_BAD_REQUEST);
            } else {
                $statistics_collection = $this->getStatisticsForAYear($carrier_code, $airport_code, $year);
            }
        } else if (
            !\is_numeric($year) ||
            !\is_numeric($month) ||
            !\is_string($airport_code) ||
            !$this->sanitizeDate($month, $year)
        ) {
            return response('Bad syntax', Response::HTTP_BAD_REQUEST);
        } else {
            $statistics_collection = collect([$this->getStatistic($carrier_code, $airport_code, $year, $month)]);
        }

        if ($statistics_collection === null) {
            return response('Error getting statistics from statistics table', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $flight_statistics_array = $this->getFlightStatisticsArray($statistics_collection, $filter, $route);

        if ($flight_statistics_array === null) {
            return response('Error getting flight statistics from flight_statistics table', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $content_type_requested = $request->header('Content-Type');

        $response_headers = [
            'Content-Type' => $content_type_requested ?? 'application/json',
        ];

        if ($content_type_requested == 'text/csv') {
            $callback = function () use ($flight_statistics_array) {
                $FH = fopen('php://output', 'w');
                foreach ($flight_statistics_array as $row) {
                    fputcsv($FH, $row);
                }
                fclose($FH);
            };

            return response()->stream(
                $callback, Response::HTTP_OK, $response_headers
            );
        } elseif ($content_type_requested == 'application/json' || $content_type_requested == null) {
            return response()->json($flight_statistics_array, Response::HTTP_OK, $response_headers);
        }

        return response('Content-Type given is not supported.', 400);
    }

    /**
     * @param Request $request
     * @param string $carrier_code
     * @return Response
     */
    public function post(Request $request, string $carrier_code)
    {
        $airport_code = $request['airport_code'];
        $year = $request['year'];
        $month = $request['month'];

        if (
            !\is_numeric($year) ||
            !\is_numeric($month) ||
            !\is_string($airport_code) ||
            !$this->sanitizeDate($month, $year)
        ) {
            return response('Bad syntax', Response::HTTP_BAD_REQUEST);
        }

        $statistics = $this->getStatistic($carrier_code, $airport_code, $year, $month);

        if ($statistics == null) {
            $statistics = $this->createStatistics($carrier_code, $airport_code, $year, $month);
        }

        if ($statistics === null) {
            return response('Error when creating a statistic', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $statistics_id = $statistics['id'];

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
                return response('Insert succeeded', Response::HTTP_OK);
            } catch (\Exception $e) {
                return response('Unable to create a new flight statistic', Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } else {
            return response('There is already flight statistic existing', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param Request $request
     * @param string $carrier_code
     * @return Response
     */
    public function delete(Request $request, string $carrier_code)
    {
        $airport_code = $request['airport_code'];
        $year = $request['year'];
        $month = $request['month'];

        if (
            !\is_numeric($year) ||
            !\is_numeric($month) ||
            !\is_string($airport_code) ||
            !$this->sanitizeDate($month, $year)
        ) {
            return response('Bad syntax', Response::HTTP_BAD_REQUEST);
        }

        $statistics_id = $this->getStatistic($carrier_code, $airport_code, $year, $month)['id'];

        if ($statistics_id == null) {
            return response('There is nothing to delete', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $flight_statistics = $this->getFlightStatistics($statistics_id);

        if ($flight_statistics == null) {
            return response('There is nothing to delete', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        try {
            FlightStatistic::where('statistics_id', '=', $statistics_id)->delete();
            return response('Delete succeeded', Response::HTTP_OK);
        } catch (\Exception $e) {
            return response('Unable to delete flight statistics', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param Request $request
     * @param string $carrier_code
     * @return Response
     */
    public function put(Request $request, string $carrier_code)
    {
        $airport_code = $request['airport_code'];
        $year = $request['year'];
        $month = $request['month'];

        if (
            !\is_numeric($year) ||
            !\is_numeric($month) ||
            !\is_string($airport_code) ||
            !$this->sanitizeDate($month, $year)
        ) {
            return response('Bad syntax', Response::HTTP_BAD_REQUEST);
        }

        $statistics_id = $this->getStatistic($carrier_code, $airport_code, $year, $month)['id'];

        if ($statistics_id == null) {
            return response('There is nothing to update', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $flight_statistics = $this->getFlightStatistics($statistics_id);

        if ($flight_statistics == null) {
            return response('There is nothing to update', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        try {
            if ($request['cancelled']) {
                FlightStatistic::where(
                    'statistics_id',
                    '=',
                    $statistics_id
                )->update(['cancelled' => $request['cancelled']]);
            }


            if ($request['on_time']) {
                FlightStatistic::where(
                    'statistics_id',
                    '=',
                    $statistics_id
                )->update(['on_time' => $request['on_time']]);
            }

            if ($request['total']) {
                FlightStatistic::where(
                    'statistics_id',
                    '=',
                    $statistics_id
                )->update(['total' => $request['total']]);
            }

            if ($request['delayed']) {
                FlightStatistic::where(
                    'statistics_id',
                    '=',
                    $statistics_id
                )->update(['delayed' => $request['delayed']]);
            }

            if ($request['diverted']) {
                FlightStatistic::where(
                    'statistics_id',
                    '=',
                    $statistics_id
                )->update(['diverted' => $request['diverted']]);
            }
            return response('Update succeed', Response::HTTP_OK);
        } catch (\Exception $e) {
            return response('Unable to update', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * A function to check if an input date is valid.
     * @param int $month
     * @param int $year
     * @return bool
     */
    private function sanitizeDate(int $month, int $year)
    {
        return ($month > 0 && $month < 13 && $year <= date('Y') && $year > 1000);
    }

    /**
     * @param string $carrier_code
     * @param string $airport_code
     * @param int $year
     * @param int $month
     * @return Statistic|null
     */
    private function getStatistic(string $carrier_code, string $airport_code, int $year, int $month)
    {
        try {
            return Statistic::where(
                [
                    'carrier_code' => $carrier_code,
                    'airport_code' => $airport_code,
                    'month' => $month,
                    'year' => $year
                ],
                '='
            )->first();
        } catch (\Exception $e) {
            return null;
        }
    }


    /**
     * @param string $carrier_code
     * @param string $airport_code
     * @param int $year
     * @return Collection|null
     */
    private function getStatisticsForAYear(string $carrier_code, string $airport_code, int $year)
    {
        try {
            return Statistic::where(
                [
                    'carrier_code' => $carrier_code,
                    'airport_code' => $airport_code,
                    'year' => $year
                ],
                '='
            )->get();
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * @param string $carrier_code
     * @param string $airport_code
     * @param int $month
     * @return Collection|null
     */
    private function getStatisticsForAMonth(string $carrier_code, string $airport_code, int $month)
    {
        try {
            return Statistic::where(
                [
                    'carrier_code' => $carrier_code,
                    'airport_code' => $airport_code,
                    'month' => $month
                ],
                '='
            )->get();
        } catch (\Exception $e) {
            return null;
        }
    }


    /**
     * @param string $carrier_code
     * @param string $airport_code
     * @return Collection|null
     */
    private function getStatisticsForAll(string $carrier_code, string $airport_code)
    {
        try {
            $statistics_array = Statistic::where(['carrier_code' => $carrier_code, 'airport_code' => $airport_code], '=')->get();
        } catch (\Exception $e) {
            return null;
        }

        return $statistics_array;
    }

    /**
     * @param array $filter
     * @param FlightStatistic $flight_statistics
     * @return array
     */
    private function getStatisticsResult(array $filter, FlightStatistic $flight_statistics)
    {
        $statistics_result = [];
        if (empty($filter)) {
            $statistics_result['cancelled'] = $flight_statistics->cancelled;
            $statistics_result['delayed'] = $flight_statistics->delayed;
            $statistics_result['on_time'] = $flight_statistics->on_time;
            $statistics_result['diverted'] = $flight_statistics->diverted;
            $flight_statistics['total'] = $flight_statistics->total;
        } else {
            if (in_array('cancelled', $filter)) {
                $statistics_result['cancelled'] = $flight_statistics->cancelled;
            }
            if (in_array('delayed', $filter)) {
                $statistics_result['delayed'] = $flight_statistics->delayed;
            }
            if (in_array('on_time', $filter)) {
                $statistics_result['on_time'] = $flight_statistics->on_time;
            }
            if (in_array('diverted', $filter)) {
                $statistics_result['diverted'] = $flight_statistics->diverted;
            }
            if (in_array('total', $filter)) {
                $flight_statistics['total'] = $flight_statistics->total;
            }
        }
        return $statistics_result;
    }


    /**
     * @param Collection $statistics_array
     * @param array $filter
     * @param string $route
     * @return array
     */
    private function getFlightStatisticsArray(Collection $statistics_array, array $filter, string $route)
    {
        $flight_statistics_array = [];

        foreach ($statistics_array as $statistic) {

            $flight_statistics = $this->getFlightStatistics($statistic->id);

            if ($flight_statistics != null) {
                $flight_statistics_array[] =
                    [
                        'route' => $route,
                        'month' => $statistic->month,
                        'year' => $statistic->year,
                        'statistics_result' => $this->getStatisticsResult($filter, $flight_statistics)
                    ];
            }
        }
        return $flight_statistics_array;
    }

    /**
     * @param string $carrier_code
     * @param string $airport_code
     * @param int $year
     * @param int $month
     * @return Statistic|null
     */
    private function createStatistics(string $carrier_code, string $airport_code, int $year, int $month)
    {
        try {
            return Statistic::create([
                'carrier_code' => $carrier_code,
                'airport_code' => $airport_code,
                'month' => $month,
                'year' => $year
            ]);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * @param int $statistics_id
     * @return FlightStatistic|null
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
