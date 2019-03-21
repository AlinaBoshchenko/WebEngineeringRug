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
     * @param Request $request
     * @param string|null $carrier_code
     *
     * @return Response
     */
    public function get(Request $request, $carrier_code = null)
    {
        if ($carrier_code !== null && \is_string($carrier_code)) {
            $content_body = $this->getCarrierAsArray($carrier_code);
        } else {
            $content_body = $this->getCarriersAsArray();
        }

        if ($content_body === null) {
            return response('Problem loading from airports database.', Response::HTTP_INTERNAL_SERVER_ERROR);
        } elseif (empty($content_body)) {
            return response('Carrier code not found.', Response::HTTP_NOT_FOUND);
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
     * Get all carriers as arrays.
     *
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
        foreach ($carriers as $carrier) {
            $carriers_as_array[] = \array_merge(
                $carrier->toArray(),
                [
                    'link' => URL::route('api_get_carriers', $carrier->carrier_code)
                ]
            );
        }

        return $carriers_as_array;
    }

    /**
     * Get a carrier with its carrier_code.
     *
     * @param string $carrier_code
     * @param bool   $include_extras
     *
     * @return array|null
     */
    public function getCarrierAsArray(string $carrier_code, bool $include_extras = false)
    {
        try {
            $carrier = Carrier::where('carrier_code' , '=' , $carrier_code)->first();
        } catch (\Exception $e){
            return null;
        }

        if (empty($carrier)) {
            return [];
        }

        if ($include_extras) {
            return \array_merge(
                $carrier->toArray(),
                [
                    'link' => URL::route('api_get_carriers', $carrier->carrier_code)
                ]
            );
        }

        return $carrier->toArray();
    }
}
