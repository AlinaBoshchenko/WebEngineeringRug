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


Route::get('API/carrier/{carrier_code}/statistics/flights', 'PagesController@getCarrierStatistics');
Route::post('API/carrier/{carrier_code}/statistics/flights', 'PagesController@postCarrierStatistics');
Route::delete('API/carrier/{carrier_code}/statistics/flights', 'PagesController@deleteCarrierStatistics');
Route::put('API/carrier/{carrier_code}/statistics/flights', 'PagesController@putCarrierStatistics');
Route::get('API/carriers/statistics/minutes_delayed', 'PagesController@getCarriersStatisticsMinutes');

Route::get('API/carrier/{carrier_code}/statistics/delays', 'PagesController@getCarrierStatisticsDelays');
