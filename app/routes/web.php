<?php

/**
 * Views endpoints
 */

Route::get('airports', 'PagesController@getAirportsPage');
Route::get('about', 'PagesController@getAbout');
Route::get('contact', 'PagesController@getContact');
Route::get('/', 'PagesController@getIntro');
Route::get('carriers', 'PagesController@getCarriersPage');
Route::get('carriers/{carrier_code}', 'PagesController@getCarrierPage');
Route::get('airports/{airport_code}', 'PagesController@getAirportPage');
Route::get('carriers/statistics/delays', 'PagesController@getDelaysPage');
Route::get('/statistics', 'PagesController@getStatPage');
Route::get('/statistics/carrier', 'PagesController@getStatCarPage');
Route::get('/carriers/{carrier_code}/statistics/delays', 'PagesController@getDelaysCarPage');
Route::get('/carriers/{carrier_code}/statistics/flights', 'PagesController@getFlightsStatPage');
Route::get('/statistics/flights', 'PagesController@getStatisticsFlightsPage');
Route::get('/statistics/minutes_delayed', 'PagesController@getStatisticsMinPage');
Route::get('/carriers/statistics/minutes_delayed', 'PagesController@getStatMinPage');
Route::get('/ranking', 'PagesController@getRankingPage');
Route::get('/carriers/rankings/number_of_delays', 'PagesController@getRateDelaysPage');
Route::get('/carriers/rankings/ratio_of_cancellations', 'PagesController@getRateCanPage');
Route::get('/reviews/post', 'PagesController@postReviewPage');
Route::get('/statistics/delete', 'PagesController@deleteStatPage');
Route::get('/carriers/{carrier_code}/statistics/flights/update', 'PagesController@updateStatPage');
Route::get('/carriers/{carrier_code}/statistics/flights/post', 'PagesController@postStatPage');
Route::get('/reviews/byuser', 'PagesController@getReviewsPage');
Route::get('/reviews/{username}', 'PagesController@getByUserPage');
Route::get('/reviews/byuser/byid', 'PagesController@getReviewPage');
Route::get('/reviews/{user_name}/{review_id}', 'PagesController@getByIdPage');
Route::get('/additional', 'PagesController@getExternalPage');
/**
 * API endpoints
 */

Route::get('API/airports/{airport_code?}', [
        'as' => 'api_get_airports',
        'uses' => 'AirportsController@get'
    ]
);

Route::get('API/carriers/{carrier_code?}', [
        'as' => 'api_get_carriers',
        'uses' => 'CarriersController@get']
);

Route::get('API/airports/{airport_code}/carriers', [
        'as' => 'carriers_from_airport',
        'uses' => 'CarriersFromAirportController@get'
    ]
);

Route::get('API/carriers/statistics/delays', [
        'as' => 'api_carrier_delayed_statistics',
        'uses' => 'CarrierDelayedStatisticsController@get'
    ]
);

Route::get('API/carriers/{carrier_code}/statistics/delays', [
        'as' => 'api_specific_carrier_delayed_statistics',
        'uses' => 'CarrierDelayedStatisticsController@get'
    ]
);

Route::get('API/carriers/{carrier_code}/statistics/flights', [
        'as' => 'api_get_flight_statistics',
        'uses' => 'FlightStatisticsController@get'
    ]
);

Route::post('API/carriers/{carrier_code}/statistics/flights', [
        'as' => 'api_post_flight_statistics',
        'uses' => 'FlightStatisticsController@post'
    ]
);

Route::delete('API/carriers/{carrier_code}/statistics/flights', [
        'as' => 'api_delete_flight_statistics',
        'uses' => 'FlightStatisticsController@delete'
    ]
);

Route::put('API/carriers/{carrier_code}/statistics/flights', [
        'as' => 'api_put_flight_statistics',
        'uses' => 'FlightStatisticsController@put'
    ]
);

Route::get('API/carriers/statistics/minutes_delayed', [
        'as' => 'api_get_minute_delay',
        'uses' => 'MinuteStatisticsController@get'
    ]
);

Route::get('API/carriers/rankings/{ranking_type}', [
        'as' => 'api_get_number_of_delays_ranking',
        'uses' => 'RankingsController@get'
    ]
);

// UPDATE REQUEREMENTS DOCUMENT
Route::post('API/reviews}', [
        'as' => 'api_post_review',
        'uses' => 'UserReviewsController@post'
    ]
);

Route::get('API/reviews/{user_name}', [
        'as' => 'api_get_reviews',
        'uses' => 'UserReviewsController@get'
    ]
);

Route::get('API/reviews/{user_name}/{review_id}', [
        'as' => 'api_get_review',
        'uses' => 'UserReviewsController@get'
    ]
);
