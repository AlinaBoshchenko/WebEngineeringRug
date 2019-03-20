<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('airports', 'PagesController@getAirportsPage');
Route::get('about', 'PagesController@getAbout');
Route::get('contact', 'PagesController@getContact');
Route::get('intro', 'PagesController@getIntro');

//Henry
Route::get('API/airports/{airport_code?}', ['as' => 'api_get_airports', 'uses' => 'AirportsController@get']);
Route::get('API/carriers/{carrier_code?}', ['as' => 'api_get_carriers', 'uses' => 'CarriersController@get']);
Route::get('API/airports/{airport_code}/carriers', 'CarriersFromAirportController@get');
Route::get('API/carriers/statistics/delays', 'CarrierDelayedStatistics@get');


Route::get('API/carrier/{carrier_code}/statistics/flights', ['as' => 'api_get_flight_statistics', 'uses' => 'FlightStatisticsController@get']);
Route::post('API/carrier/{carrier_code}/statistics/flights', ['as' => 'api_post_flight_statistics', 'uses' => 'FlightStatisticsController@post']);
Route::delete('API/carrier/{carrier_code}/statistics/flights', ['as' => 'api_delete_flight_statistics', 'uses' => 'FlightStatisticsController@delete']);
Route::put('API/carrier/{carrier_code}/statistics/flights', ['as' => 'api_put_flight_statistics', 'uses' => 'FlightStatisticsController@put']);
Route::get('API/carriers/statistics/minutes_delayed', ['as' => 'api_get_minute_delay', 'uses' => 'MinuteStatisticsController@get']);

Route::get('API/carrier/{carrier_code}/statistics/delays', 'PagesController@getCarrierStatisticsDelays');
