<?php

/**
 * Views endpoints
 */

Route::get('airports', 'PagesController@getAirportsPage');
Route::get('about', 'PagesController@getAbout');
Route::get('contact', 'PagesController@getContact');
Route::get('intro', 'PagesController@getIntro');

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

