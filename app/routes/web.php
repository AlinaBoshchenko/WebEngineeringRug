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

Route::get('/', 'PagesController@getIndex');
Route::get('airports', 'PagesController@getAirports');
Route::get('airport/{airport_code}', 'PagesController@getAirport');
Route::get('carriers', 'PagesController@getCarriers');
Route::get('carrier/{carrier_code}', 'PagesController@getCarrier');
Route::get('airport/{airport_code}/carriers', 'PagesController@getCarriersAtAirport');
Route::get('carrier/{carrier_code}/statistics/flights', 'PagesController@getCarrierStatistics');
Route::post('carrier/{carrier_code}/statistics/flights', 'PagesController@postCarrierStatistics');
Route::delete('carrier/{carrier_code}/statistics/flights', 'PagesController@deleteCarrierStatistics');
Route::put('carrier/{carrier_code}/statistics/flights', 'PagesController@putCarrierStatistics');
Route::get('carriers/statistics/minutes_delayed', 'PagesController@getCarriersStatisticsMinutes');
Route::get('carriers/statistics/delays', 'PagesController@getCarriersStatisticsDelays');
Route::get('carrier/{carrier_code}/statistics/delays', 'PagesController@getCarrierStatisticsDelays');

