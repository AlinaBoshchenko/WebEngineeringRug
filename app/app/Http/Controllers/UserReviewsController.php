<?php

namespace App\Http\Controllers;

use App\UserReviews;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\StreamedResponse;

class UserReviewsController extends Controller
{
    /**
     * @param Request $request
     * @param string $user_name
     * @param string|null $review_id
     *
     * @return Response|StreamedResponse
     */
    public function get(Request $request, string $user_name, string $review_id = null)
    {
        if ($review_id) {
            $content_body = $this->getReviewWithGivenID($user_name, $review_id);
        } else {
            $content_body = $this->getReviewsWithGivenUserName($user_name);
        }

        if ($content_body === null) {
            return response('Problem loading from review database.', Response::HTTP_INTERNAL_SERVER_ERROR);
        } elseif (empty($content_body)) {
            return response('No reviews not found.', Response::HTTP_NOT_FOUND);
        }


        $content_type_requested = $request->header('Accept');

        $response_headers = [
            'Content-Type' => $content_type_requested == 'text/csv' ? $content_type_requested : 'application/json',
        ];

        if ($content_type_requested == 'text/csv') {
            $callback = function () use ($content_body) {
                $FH = fopen('php://output', 'w');


                foreach ($content_body as $idx => $row) {
                    $string = [
                        'user_name' => $row['user_name'],
                        'review' => $row['reviews'],
                        'carrier_code_rank_1' => $row['carrier_code_rank_1'],
                        'carrier_code_rank_2' => $row['carrier_code_rank_2'],
                        'carrier_code_rank_3' => $row['carrier_code_rank_3'],
                    ];

                    foreach ($row['timestamp'] as $key => $timestamp){
                        $string[$key] = $timestamp;
                    }

                    if ($idx == 0) {
                        fputcsv($FH, \array_keys($string));
                    }

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
     * @param Request $request
     * @param string $user_name
     *
     * @return Response
     */
    public function post(Request $request, string $user_name)
    {
        $review = $request['review'] ?? null;
        $carrier_code_rank_1 = $request['carrier_code_rank_1'] ?? null;
        $carrier_code_rank_2 = $request['carrier_code_rank_2'] ?? null;
        $carrier_code_rank_3 = $request['carrier_code_rank_3'] ?? null;

        if (!\is_string($user_name)) {
            return response("Bad user name given", Response::HTTP_BAD_REQUEST);
        }

        if (
            $review === null ||
            $carrier_code_rank_1 === null ||
            $carrier_code_rank_2 === null ||
            $carrier_code_rank_3 === null
        ) {
            return response("You must fill up the blank", Response::HTTP_BAD_REQUEST);
        }

        try {
            UserReviews::create(
                [
                    'user_name' => $user_name,
                    'reviews' => $review,
                    'carrier_code_rank_1' => $carrier_code_rank_1,
                    'carrier_code_rank_2' => $carrier_code_rank_2,
                    'carrier_code_rank_3' => $carrier_code_rank_3
                ]
            );
            return response('Review submission succeeded', Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response('Unable to submit your review', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * @param string $user_name
     * @param int $id
     *
     * @return array|null
     */
    private function getReviewWithGivenID(string $user_name, int $id){
        try{
            $review = UserReviews::where('id' , '=' , $id)->first();
            if($review && $review->user_name == $user_name){
                return $review;
            }else{
                return null;
            }
        } catch (\Exception $exception){
            return null;
        }
    }

    /**
     * @param string $user_name
     *
     * @return array|null
     */
    private function getReviewsWithGivenUserName(string $user_name)
    {
        try{
            $reviews =  UserReviews::where('user_name' , '=' , $user_name)->get();
        } catch (\Exception $exception){
            return null;
        }

        if (empty($reviews)) {
            return [];
        }

        $reviews_as_array = [];
        foreach ($reviews as $review) {
            $reviews_as_array[] = \array_merge(
                $review->toArray(),
                [
                    'link' => URL::route('api_get_review', ['user_name' => $user_name, 'review_id' => $review['id']])
                ]
            );
        }

        return $reviews_as_array;
    }
}
