<?php

namespace App\Http\Controllers;

use App\carrier;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\URL;

/**
 * Handles requests for the 'carriers' API endpoint.
 */
class CarriersController extends Controller
{
    /**
     * Returns all the carriers in the desired format.
     *
     * @param Request $request Content-Type: 'application/json'|'text/csv'|null
     * @param string|null $carrier_code
     *
     * @return Response
     */
    public function get(Request $request, $carrier_code = null)
    {
        if (\is_string($carrier_code)) {
            $content_body = $this->getCarrierAsArray($carrier_code);
        } else {
            $content_body = $this->getCarriersAsArray();
        }

        if ($content_body === null) {
            return response('Problem loading from airports database.', Response::HTTP_INTERNAL_SERVER_ERROR);
        } elseif (empty($content_body)) {
            return response('Carrier code not found.', Response::HTTP_NOT_FOUND);
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
     * @return array|null
     */
    private function getCarriersAsArray()
    {
        try {
            $carriers = Carrier::all();
        } catch (\Exception $e){
            return null;
        }

        if (empty($carriers)) {
            return [];
        }

        $carriers_as_array = [];
        foreach ($carriers->toArray() as $carrier) {
            $carriers_as_array[] =
                [
                    'carrier_name' => $carrier['carrier_code'],
                    'carrier_code' => $carrier['carrier_name'],
                    'link' => URL::route('api_get_carriers', $carrier['carrier_code'])
                ];
        }

        return $carriers_as_array;
    }

    /**
     * @param string $carrier_code
     *
     * @return array|null
     */
    private function getCarrierAsArray(string $carrier_code)
    {
        try {
            $carrier = Carrier::where('carrier_code' , '=' , $carrier_code)->first();
        } catch (\Exception $e){
            return null;
        }

        if (empty($carrier)) {
            return [];
        }

        $carrier_as_array = $carrier->toArray();

        return  [
            'carrier_name' => $carrier_as_array['carrier_code'],
            'carrier_code' => $carrier_as_array['carrier_name'],
        ];
    }
}
