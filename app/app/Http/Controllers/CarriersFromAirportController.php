<?php

namespace App\Http\Controllers;

use App\Carrier;
use App\CarriersFromAirport;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\URL;

/**
 * Handles requests for the 'airports' API endpoint.
 */
class CarriersFromAirportController extends Controller
{
    /**
     * Returns all the airports in the desired format.
     *
     * @param Request $request Content-Type: 'application/json'|'text/csv'|null
     * @param string $airport_code
     *
     * @return Response
     */
    public function get(Request $request, $airport_code)
    {
        if (!\is_string($airport_code)) {
            return response('Problem loading from airports database.', Response::HTTP_BAD_REQUEST);
        }

        $content_body = $this->getCarriersFromAirportCode($airport_code);

        if ($content_body === null) {
            return response('Problem loading from carriers/airports database.', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $content_type_requested = $request->header('Content-Type');

        $response_headers = [
            'Content-Type' => $content_type_requested ?? 'application/json',
        ];

        if ($content_type_requested == 'text/csv') {
            $callback = function () use ($content_body) {
                $FH = fopen('php://output', 'w');
                foreach ($content_body as $row) {
                    fputcsv($FH, $row);
                }
                fclose($FH);
            };

            return response()->stream(
                $callback, Response::HTTP_OK, $response_headers
            );
        } elseif ($content_type_requested == 'application/json' || $content_type_requested == null) {
            return response()->json($content_body, Response::HTTP_OK, $response_headers);
        }

        return response('Content-Type given is not supported.', 400);
    }

    /**
     * @param string $airport_code
     *
     * @return array|null
     */
    private function getCarriersFromAirportCode(string $airport_code)
    {
        try {
            $carriers = CarriersFromAirport::where('airport_code' , '=' , $airport_code)->get();
        } catch (\Exception $e){
            return null;
        }

        if (empty($carriers)) {
            return [];
        }

        $carriers_as_array = [];
        foreach ($carriers as $carrier) {
            $carrier = Carrier::where('carrier_code', '=', $carrier->carrier_code)->first();

            if ($carrier == null) {
                continue;
            }

            $carriers_as_array[] =
                [
                    'carrier_name' => $carrier->carrier_name,
                    'carrier_code' => $carrier->carrier_code,
                    'link' => URL::route('api_get_carriers', $carrier->carrier_code),
                ];
        }

        return $carriers_as_array;
    }
}
