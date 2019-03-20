<?php

namespace App\Http\Controllers;

use App\Airport;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\URL;

/**
 * Handles requests for the 'airports' API endpoint.
 */
class AirportsController extends Controller
{
    /**
     * Returns all the airports in the desired format.
     *
     * @param Request $request Content-Type: 'application/json'|'text/csv'|null
     * @param string|null $airport_code
     *
     * @return Response
     */
    public function get(Request $request, $airport_code = null)
    {
        if (\is_string($airport_code)) {
            $content_body = $this->getAirportAsArray($airport_code);
        } else {
            $content_body = $this->getAirportsAsArray();
        }

        if ($content_body === null) {
            return response('Problem loading from airports database.', Response::HTTP_INTERNAL_SERVER_ERROR);
        } elseif (empty($content_body)) {
            return response('No airport(s) not found.', Response::HTTP_NOT_FOUND);
        }

        $content_type_requested = $request->header('Content-Type');

        $response_headers = [
            'Content-Type' => $content_type_requested ?? 'application/json',
        ];

        if ($content_type_requested == 'text/csv') {
            $callback = function () use ($content_body) {
                $FH = fopen('php://output', 'w');
                foreach ($content_body as $row) {
                    fputcsv($FH, [$row]);
                }
                fclose($FH);
            };

            return response()->stream(
                $callback,
                Response::HTTP_OK,
                $response_headers
            );
        } elseif ($content_type_requested == 'application/json' || $content_type_requested == null) {
            return response()->json($content_body, Response::HTTP_OK, $response_headers);
        }

        return response('Content-Type given is not supported.', 400);
    }

    /**
     * Gets all airports available as arrays.
     *
     * @return array|null
     */
    private function getAirportsAsArray()
    {
        try {
            $airports = Airport::all();
        } catch (\Exception $e){
            return null;
        }

        if (empty($airports)) {
            return [];
        }

        $airports_as_array = [];
        foreach ($airports as $airport) {
            $airports_as_array[] = \array_merge(
                $airport->toArray(),
                [
                    'link' => URL::route('api_get_airports', $airport['airport_code'])
                ]
            );
        }

        return $airports_as_array;
    }

    /**
     * Returns a specific airport as an array.
     *
     * @param string $airport_code
     * @param bool  $include_extras
     *
     * @return array|null
     */
    public function getAirportAsArray(string $airport_code, bool $include_extras = false)
    {
        try {
            $airport = Airport::where('airport_code' , '=' , $airport_code)->first();
        } catch (\Exception $e){
            return null;
        }

        if (empty($airport)) {
            return [];
        }

        if ($include_extras) {
            return \array_merge(
                $airport->toArray(),
                [
                    'link' => URL::route('api_get_airports', $airport['airport_code'])
                ]
            );
        }

        return $airport->toArray();
    }
}
