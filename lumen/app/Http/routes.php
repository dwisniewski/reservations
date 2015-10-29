<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->get('/', function () use ($app) {
    return $app->welcome();
});

// USER
$app->get('api/user', 'UserController@index');
$app->get('api/user/{id}', 'UserController@getUser');
$app->post('api/user', 'UserController@saveUser');
$app->put('api/user/{id}', 'UserController@updateUser');
$app->delete('api/user/{id}', 'UserController@deleteUser');

// ROOM
$app->get('api/room', 'RoomController@index');
$app->get('api/room/{id}', 'RoomController@getRoom');
$app->post('api/room', 'RoomController@saveRoom');
$app->put('api/room/{id}', 'RoomController@updateRoom');
$app->delete('api/room/{id}', 'RoomController@deleteRoom');

// TIMEOFF
$app->get('api/timeoff', 'TimeoffController@index');
$app->get('api/timeoff/{id}', 'TimeoffController@getTimeoff');
$app->post('api/timeoff', 'TimeoffController@saveTimeoff');
$app->put('api/timeoff/{id}', 'TimeoffController@updateTimeoff');
$app->delete('api/timeoff/{id}', 'TimeoffController@deleteTimeoff');

// RESERVATION
$app->get('api/reservation', 'ReservationController@index');
$app->get('api/reservation/{id}', 'ReservationController@getReservation');
$app->post('api/reservation', 'ReservationController@saveReservation');
$app->put('api/reservation/{id}', 'ReservationController@updateReservation');
$app->delete('api/reservation/{id}', 'ReservationController@deleteReservation');

// PRICE
$app->get('api/price', 'PriceController@index');
$app->get('api/price/{id}', 'PriceController@getPrice');
$app->post('api/price', 'PriceController@savePrice');
$app->put('api/price/{id}', 'PriceController@updatePrice');
$app->delete('api/price/{id}', 'PriceController@deletePrice');

// LOCATOR
$app->get('api/locator', 'LocatorController@index');
$app->get('api/locator/{id}', 'LocatorController@getLocator');
$app->get('api/locator/{id}/reservations/', 'LocatorController@getLocatorWithReservations');
$app->post('api/locator', 'LocatorController@saveLocator');
$app->put('api/locator/{id}', 'LocatorController@updateLocator');
$app->delete('api/locator/{id}', 'LocatorController@deleteLocator');