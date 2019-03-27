<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

class RankingsController
{

    /**
     * @param Request $request
     * @param string $ranking_type
     *
     * @return array|Response|null
     */
    public function get(Request $request, string $ranking_type)
    {
        $year = $request['year'] ?? null;

        if ($year === null) {
            return response('Year is not given by the user. Rankings cannot be shown.', Response::HTTP_BAD_REQUEST);
        }

        if ($year < 1000 || $year > Date('Y')) {
            return response('Bad date given by the user. Rankings cannot be shown.', Response::HTTP_BAD_REQUEST);
        }

        if($ranking_type != 'number_of_delays' && $ranking_type != 'ratio_of_cancellations'){
            return response('Wrong ranking type is given', Response::HTTP_BAD_REQUEST);
        }

        if($ranking_type == 'number_of_delays'){
            $content_body = $this->getNumberOfDelaysRanking($year);
        } else {
            $content_body = $this->getRatioOfCancellationRanking($year);
        }

        if ($content_body === null) {
            return response('Problem loading the ranking.', Response::HTTP_INTERNAL_SERVER_ERROR);
        } elseif (empty($content_body)) {
            return response('No ranking found.', Response::HTTP_OK);
        }

        $content_type_requested = $request->header('Accept');

        $response_headers = [
            'Content-Type' => $content_type_requested == 'text/csv' ? $content_type_requested : 'application/json',
        ];

        if ($content_type_requested == 'text/csv') {
            $callback = function () use ($content_body) {
                $FH = fopen('php://output', 'w');
                foreach ($content_body as $row) {
                    if (!\is_array($row)) {
                        $row = [$row];
                    }
                    fputcsv($FH, $row);
                }
                fclose($FH);
            };

            return response()->stream(
                $callback,
                Response::HTTP_OK,
                $response_headers
            );
        } else {
            return response()->json($content_body, Response::HTTP_OK, $response_headers);
        }
    }

    /**
     * @param String $year
     *
     * @return array|null
     */
    private function getNumberOfDelaysRanking(String $year)
    {
        try{
            $results = DB::select(
                "SELECT statistics.carrier_code, SUM(flight_statistics.delayed) as num_of_delays 
                        FROM statistics JOIN flight_statistics 
                        ON statistics.id=flight_statistics.statistics_id
                        WHERE year = :year 
                        GROUP BY carrier_code 
                        ORDER BY num_of_delays;",
                ['year' => $year]
            );
        }catch (\Exception $e){
            return null;
        }

        $number_of_delays_ranking = [];
        foreach ($results as $index => $result){
            $result = get_object_vars($result);
            $number_of_delays_ranking[] = [
                'rank' => $index+1,
                'carrier_code' => $result['carrier_code'],
                'num_of_delays' => $result['num_of_delays'],
                'carrier_link' => URL::route('api_get_carriers', $result['carrier_code'])
            ];
        }
        return $number_of_delays_ranking;
    }

    /**
     * @param $year
     *
     * @return array|null
     */
    private function getRatioOfCancellationRanking($year)
    {
        try{
            $results = DB::select(
                "SELECT statistics.carrier_code, SUM(flight_statistics.cancelled)/SUM(flight_statistics.total) as ratio_of_cancellation 
                        FROM statistics 
                        JOIN flight_statistics 
                        ON statistics.id=flight_statistics.statistics_id 
                        WHERE year = :year 
                        GROUP BY carrier_code 
                        ORDER BY ratio_of_cancellation;",
                ['year' => $year]
            );
        }catch (\Exception $e){
            return null;
        }

        $ratio_of_cancellation_ranking = [];
        foreach ($results as $index => $result){
            $result = get_object_vars($result);
            $ratio_of_cancellation_ranking[] = [
                'rank' => $index+1,
                'carrier_code' => $result['carrier_code'],
                'ratio_of_cancellations' => $result['ratio_of_cancellation'],
                'carrier_link' => URL::route('api_get_carriers', $result['carrier_code'])
            ];
        }
        return $ratio_of_cancellation_ranking;
    }
}
