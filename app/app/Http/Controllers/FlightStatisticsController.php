<?php
/**
 * Created by PhpStorm.
 * User: aideng
 * Date: 2019-03-17
 * Time: 11:56
 */

namespace App\Http\Controllers;

use App\FlightStatistic;
use App\Statistic;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class FlightStatisticsController
{

    private function _getStatistics($carrier_code, $airport_code, $year, $month){

        try{
            $statistics = Statistic::where(['carrier_code' => $carrier_code, 'airport_code'=>$airport_code, 'month' => $month, 'year' => $year] , '=')->first();
            return $statistics;
        } catch (\Exception $e){
            return null;
        }

    }


    private function _createStatistics($carrier_code, $airport_code, $year, $month) {

        try {
            $statistics = Statistic::create([
                'carrier_code' => $carrier_code,
                'airport_code'=>$airport_code,
                'month' => $month,
                'year' => $year
            ]);
            return $statistics;
        } catch (\Exception $e){
            return response('unable to create statistics', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function _getFlightStatistics($statistics_id){
        try{
            $flight_statistics = FlightStatistic::where('statistics_id', '=', $statistics_id)->first();
            return $flight_statistics;
        } catch (\Exception $e){
            return null;
        }
    }


    public function get(Request $request, $carrier_code)
    {
        $airport_code = $request['airport_code'];
        $year = $request['year'];
        $month = $request['month'];

        $statistics_id = $this->_getStatistics($carrier_code, $airport_code, $year, $month)['id'];

        if ($statistics_id == null) {
            return response('no corresponding statistics', Response::HTTP_NOT_FOUND);
        }

        $flight_statistics = $this->_getFlightStatistics($statistics_id);

        if($flight_statistics == null){
            return response('no corresponding statistics', Response::HTTP_NOT_FOUND);
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

    public function post(Request $request, $carrier_code)
    {

        $airport_code = $request['airport_code'];
        $year = $request['year'];
        $month = $request['month'];

        $statistics_id = $this->_getStatistics($carrier_code, $airport_code, $year, $month)['id'];

        if ($statistics_id == null) {
            $statistics_id = $this->_createStatistics($carrier_code, $airport_code, $year, $month)['id'];
        }

        if ($this->_getFlightStatistics($statistics_id) == null) {

            try {
                FlightStatistic::create([
                    'statistics_id' => $statistics_id,
                    'cancelled' => $request['cancelled'],
                    'on_time' => $request['on_time'],
                    'total' => $request['total'],
                    'delayed' => $request['delayed'],
                    'diverted' => $request['diverted']
                ]);
                return response('insert succeed', Response::HTTP_OK);
            } catch (\Exception $e) {
                return response('unable to create a new flightstatistics', Response::HTTP_INTERNAL_SERVER_ERROR);
            }

        } else {
            //TODO AiDeng discuss with the team
            return 0;
        }

    }


    public function delete(Request $request, $carrier_code)
    {
        $airport_code = $request['airport_code'];
        $year = $request['year'];
        $month = $request['month'];

        $statistics_id = $this->_getStatistics($carrier_code, $airport_code, $year, $month)['id'];

        if($statistics_id == null) {
            return response('there is nothing to delete', Response::HTTP_INTERNAL_SERVER_ERROR);
        }


        $flight_statistics = $this->_getFlightStatistics($statistics_id);

        if ($flight_statistics == null) {
            return response('there is nothing to delete', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        try {
            FlightStatistic::where('statistics_id', '=', $statistics_id)->delete();
            return response('delete succeed', Response::HTTP_OK);
        } catch (\Exception $e) {
            return response('unable to delete flightstatistics', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }


    public function put(Request $request, $carrier_code)
    {
        $airport_code = $request['airport_code'];
        $year = $request['year'];
        $month = $request['month'];

        $statistics_id = $this->_getStatistics($carrier_code, $airport_code, $year, $month)['id'];

        if($statistics_id == null) {
            return response('there is nothing to update', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $flight_statistics = $this->_getFlightStatistics($statistics_id);

        if ($flight_statistics == null) {
            return response('there is nothing to update', Response::HTTP_INTERNAL_SERVER_ERROR);
        }



        try{
            if($request['cancelled']){
                FlightStatistic::where('statistics_id', '=', $statistics_id)->update(['cancelled'=>$request['cancelled']]);
            }


            if($request['on_time']){
                FlightStatistic::where('statistics_id', '=', $statistics_id)->update(['on_time'=>$request['on_time']]);
            }

            if($request['total']){
                FlightStatistic::where('statistics_id', '=', $statistics_id)->update(['total'=>$request['total']]);
            }

            if ($request['delayed']){
                FlightStatistic::where('statistics_id', '=', $statistics_id)->update(['delayed'=>$request['delayed']]);
            }

            if ($request['diverted']){
                FlightStatistic::where('statistics_id', '=', $statistics_id)->update(['diverted'=>$request['diverted']]);
            }
            return response('update succeed', Response::HTTP_OK);

        }catch (\Exception $e){

            return response('unable to update', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }









}