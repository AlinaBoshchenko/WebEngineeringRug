<?php

namespace App\Http\Controllers;

use App\FlightStatistic;
use App\Statistic;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Handles requests for the 'carriers/{carrier_code}/statistics/flights' API endpoint.
 */
class FlightStatisticsController
{
    /**
     * Returns flight statistics for a given carrier.
     *
     * @param Request $request
     * @param $carrier_code
     *
     * @return Response|StreamedResponse
     */
    public function get(Request $request, string $carrier_code)
    {
        $airport_code = $request['airport_code'] ?? null;
        $year = $request['year'] ?? null;
        $month = $request['month'] ?? null;
        $route = $request['route'] ?? null;
        $filter = $request['filter'] ?? null;

        if ($route === null || ($route != 'from' && $route != 'to')) {
            return response('Route is not given by user', Response::HTTP_BAD_REQUEST);
        }

        if($airport_code === null){
            return response('Airport code is not given by user', Response::HTTP_BAD_REQUEST);
        }

        if ($filter === null) {
            $filter = [];
        }

        if ($year === null && $month === null) {
            $statistics_collection = $this->getStatisticsForAll($carrier_code, $airport_code);
        } else if ($year === null) {
            if (!\is_numeric($month) || $month < 1 || $month > 12) {
                return response('Bad syntax', Response::HTTP_BAD_REQUEST);
            } else {
                $statistics_collection = $this->getStatisticsForAMonth($carrier_code, $airport_code, $month);
            }
        } else if ($month === null) {
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

        if(empty($statistics_collection)) {
            return response('Statistics not found', Response::HTTP_NOT_FOUND);
        }

        $flight_statistics_array = $this->getFlightStatisticsArray($statistics_collection, $filter, $route);

        if ($flight_statistics_array === null) {
            return response('Error getting flight statistics from flight_statistics table', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        if(empty($flight_statistics_array)) {
            return response('Flight statistics not found', Response::HTTP_NOT_FOUND);
        }

        $content_type_requested = $request->header('Accept');

        $response_headers = [
            'Content-Type' => $content_type_requested == 'text/csv' ? $content_type_requested : 'application/json',
        ];

        if ($content_type_requested == 'text/csv') {
            $callback = function () use ($flight_statistics_array) {
                $FH = fopen('php://output', 'w');
                foreach ($flight_statistics_array as $idx => $row) {
                    $string = ['route' => $row['route'], 'year' => $row['year'], 'month' => $row['month']];
                    foreach ($row['statistics'] as $key => $statistic){
                        $string[$key] = $statistic;
                    }

                    if ($idx == 0) {
                        fputcsv($FH, \array_keys($string));
                    }

                    fputcsv($FH, $string);
                }
                fclose($FH);
            };

            return response()->stream($callback, Response::HTTP_OK, $response_headers);
        } else {
            return response()->json($flight_statistics_array, Response::HTTP_OK, $response_headers);
        }
    }

    /**
     * Post new flight statistics.
     *
     * @param Request $request
     * @param string $carrier_code
     *
     * @return Response
     */
    public function post(Request $request, string $carrier_code)
    {
        $airport_code = $request['airport_code'] ?? null;
        $year = $request['year'] ?? null;
        $month = $request['month'] ?? null;

        if($airport_code === null || $year === null || $month === null){
            return response('Required input is not given by the user', Response::HTTP_BAD_REQUEST);
        }

        if (
            !\is_numeric($year) ||
            !\is_numeric($month) ||
            !\is_string($airport_code) ||
            !$this->sanitizeDate($month, $year)
        ) {
            return response('Invalid dates or airport _code given', Response::HTTP_BAD_REQUEST);
        }

        $statistic = $this->getStatistic($carrier_code, $airport_code, $year, $month);

        if ($statistic === null) {
            $statistic = $this->createStatistics($carrier_code, $airport_code, $year, $month);
            $added_statistic = true;
        } else {
            $added_statistic = false;
        }

        if ($statistic === null) {
            return response('Error when creating a statistic', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        if(empty($statistics_collection)) {
            return response('Statistics not found', Response::HTTP_NOT_FOUND);
        }

        if ($this->getFlightStatistic($statistic->id) === null) {
            if ($this->isWrongStatisticsInput($request)) {
                if ($added_statistic) {
                    $this->deleteStatistics($statistic->id);
                }
                return response('Bad syntax', Response::HTTP_BAD_REQUEST);
            }

            try {
                FlightStatistic::create(
                    [
                        'statistics_id' => $statistic->id,
                        'cancelled' => $request['cancelled'] ?? null,
                        'on_time' => $request['on_time'] ?? null,
                        'total' => $request['total'] ?? null,
                        'delayed' => $request['delayed'] ?? null,
                        'diverted' => $request['diverted'] ?? null
                    ]
                );
                return response('Insertion succeeded', Response::HTTP_CREATED);
            } catch (\Exception $e) {
                if($added_statistic){
                    $this->deleteStatistics($statistic->id);
                }
                return response('Unable to create a new flight statistic', Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } else {
            return response('There is already flight statistic existing', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param Request $request
     * @param string $carrier_code
     *
     * @return Response
     */
    public function delete(Request $request, string $carrier_code)
    {
        $airport_code = $request['airport_code'] ?? null;
        $year = $request['year'] ?? null;
        $month = $request['month'] ?? null;

        if($airport_code === null || $year === null || $month === null){
            return response('Required input is not given by the user', Response::HTTP_BAD_REQUEST);
        }

        if (
            !\is_numeric($year) ||
            !\is_numeric($month) ||
            !\is_string($airport_code) ||
            !$this->sanitizeDate($month, $year)
        ) {
            return response('Bad syntax', Response::HTTP_BAD_REQUEST);
        }

        $statistic = $this->getStatistic($carrier_code, $airport_code, $year, $month);

        if ($statistic === null) {
            return response('There is nothing to delete', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $flight_statistics = $this->getFlightStatistic($statistic->id);

        if ($flight_statistics === null) {
            return response('There is nothing to delete', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        try {
            FlightStatistic::where('statistics_id', '=', $statistic->id)->delete();
            return response('Deletion succeeded', Response::HTTP_OK);
        } catch (\Exception $e) {
            return response('Unable to delete flight statistics', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param Request $request
     * @param string $carrier_code
     *
     * @return Response
     */
    public function put(Request $request, string $carrier_code)
    {
        $airport_code = $request['airport_code'] ?? null;
        $year = $request['year'] ?? null;
        $month = $request['month'] ?? null;

        if($airport_code === null || $year === null || $month === null){
            return response('Required input is not given by the user', Response::HTTP_BAD_REQUEST);
        }

        if (
            !\is_numeric($year) ||
            !\is_numeric($month) ||
            !\is_string($airport_code) ||
            !$this->sanitizeDate($month, $year)
        ) {
            return response('Bad syntax', Response::HTTP_BAD_REQUEST);
        }

        if($this->isNotCompleteStatisticArray($request) || $this->isWrongStatisticsInput($request)){
            return response('Bad syntax', Response::HTTP_BAD_REQUEST);
        }

        $statistic = $this->getStatistic($carrier_code, $airport_code, $year, $month);

        if ($statistic === null) {
            return response('There is nothing to update', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $flight_statistics = $this->getFlightStatistic($statistic->id);

        if ($flight_statistics === null) {
            return response('There is nothing to update', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        try {
            if ($request['cancelled']) {
                FlightStatistic::where(
                    'statistics_id',
                    '=',
                    $statistic->id
                )->update(['cancelled' => $request['cancelled']]);
            }


            if ($request['on_time']) {
                FlightStatistic::where(
                    'statistics_id',
                    '=',
                    $statistic->id
                )->update(['on_time' => $request['on_time']]);
            }

            if ($request['total']) {
                FlightStatistic::where(
                    'statistics_id',
                    '=',
                    $statistic->id
                )->update(['total' => $request['total']]);
            }

            if ($request['delayed']) {
                FlightStatistic::where(
                    'statistics_id',
                    '=',
                    $statistic->id
                )->update(['delayed' => $request['delayed']]);
            }

            if ($request['diverted']) {
                FlightStatistic::where(
                    'statistics_id',
                    '=',
                    $statistic->id
                )->update(['diverted' => $request['diverted']]);
            }
            return response('Update succeeded', Response::HTTP_OK);
        } catch (\Exception $e) {
            return response('Unable to update', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * A function to check if an input date is valid.
     *
     * @param int $month
     * @param int $year
     *
     * @return bool
     */
    public function sanitizeDate(int $month, int $year)
    {
        return ($month > 0 && $month < 13 && $year <= date('Y') && $year > 1000 && ($year = date('Y') && $month > date('M')));
    }

    /**
     * @param string $carrier_code
     * @param string $airport_code
     * @param int $year
     * @param int $month
     *
     * @return Statistic|null
     */
    public function getStatistic(string $carrier_code, string $airport_code, int $year, int $month)
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
     *
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
     *
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
     *
     * @return Collection|null
     */
    private function getStatisticsForAll(string $carrier_code, string $airport_code)
    {
        try {
            return Statistic::where(
                [
                    'carrier_code' => $carrier_code,
                    'airport_code' => $airport_code
                ],
                '='
            )->get();
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * @param string[] $filter
     * @param FlightStatistic $flight_statistics
     *
     * @return array
     */
    private function getStatisticsResult(array $filter, FlightStatistic $flight_statistics)
    {
        if (empty($filter)) {
            return $flight_statistics->toArray();
        } else {
            $statistics_result = [];

            if (\in_array('cancelled', $filter)) {
                $statistics_result['cancelled'] = $flight_statistics->cancelled;
            }
            if (\in_array('delayed', $filter)) {
                $statistics_result['delayed'] = $flight_statistics->delayed;
            }
            if (\in_array('on_time', $filter)) {
                $statistics_result['on_time'] = $flight_statistics->on_time;
            }
            if (\in_array('diverted', $filter)) {
                $statistics_result['diverted'] = $flight_statistics->diverted;
            }
            if (\in_array('total', $filter)) {
                $flight_statistics['total'] = $flight_statistics->total;
            }
            return $statistics_result;
        }
    }


    /**
     * @param Collection $statistics_array
     * @param array $filter
     * @param string $route
     *
     * @return array
     */
    private function getFlightStatisticsArray(Collection $statistics_array, array $filter, string $route)
    {
        $flight_statistics_array = [];
        foreach ($statistics_array as $statistic) {
            $flight_statistic = $this->getFlightStatistic($statistic->id);

            if ($flight_statistic !== null) {
                $flight_statistics_array[] =
                    [
                        'route' => $route,
                        'month' => $statistic->month,
                        'year' => $statistic->year,
                        'statistics' => $this->getStatisticsResult($filter, $flight_statistic)
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
     *
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
     *
     * @return FlightStatistic|null
     */
    private function getFlightStatistic(int $statistics_id)
    {
        try {
            return FlightStatistic::where('statistics_id', '=', $statistics_id)->first();
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * @param int $id
     *
     * @return bool
     */
    private function deleteStatistics(int $id)
    {
        try{
            return (boolean)Statistic::where('id', '=', $id)->delete();
        }catch (\Exception $e){
            return false;
        }
    }

    /**
     * @param Request $request
     *
     * @return bool
     */
    private function isWrongStatisticsInput(Request $request): bool
    {
        return (
            $request['cancelled'] < 0 ||
            $request['on_time'] < 0 ||
            $request['total'] < 0 ||
            $request['delayed'] < 0 ||
            $request['diverted'] < 0
        );
    }

    /**
     * @param Request $request
     *
     * @return bool
     */
    private function isNotCompleteStatisticArray(Request $request): bool
    {
        return (
            $request['cancelled'] == null ||
            $request['on_time'] == null ||
            $request['total'] == null ||
            $request['delayed'] == null ||
            $request['diverted'] == null
        );
    }

}
