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
$app->get('api/rooms_free/{since}/{till}', 'RoomController@getFreeRooms');
$app->get('api/rooms_occupied/{since}/{till}', 'RoomController@getOccupiedRooms');
$app->get('api/rooms_excluded/{since}/{till}', 'RoomController@getExcludedRooms');
$app->get('api/rooms_occupied_or_excluded/{since}/{till}', 'RoomController@getOccupiedOrExcludedRooms');

// TIMEOFF
$app->get('api/timeoff', 'TimeoffController@index');
$app->get('api/timeoff/{id}', 'TimeoffController@getTimeoff');
$app->post('api/timeoff', 'TimeoffController@saveTimeoff');
$app->put('api/timeoff/{id}', 'TimeoffController@updateTimeoff');
$app->delete('api/timeoff/{id}', 'TimeoffController@deleteTimeoff');

// RESERVATION
$app->get('api/reservation', 'ReservationController@index');
$app->get('api/reservations-filtered/{locatorID}/{since}/{till}', 'ReservationController@getFiltered');
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
$app->get('api/locator/{id}/reservations', 'LocatorController@getLocatorWithReservations');
$app->get('api/locator_by_token/{token}', 'LocatorController@getLocatorByPattern');
$app->post('api/locator', 'LocatorController@saveLocator');
$app->put('api/locator/{id}', 'LocatorController@updateLocator');
$app->delete('api/locator/{id}', 'LocatorController@deleteLocator');



/*
$app->get('api/room_temp_free/{since}/{till}', 'RoomController@temporalRoomsFree');
$app->get('api/room_temp_occupied/{since}/{till}', 'RoomController@temporalRoomsOccupied');
$app->get('api/room_temp_excluded/{since}/{till}', 'RoomController@temportalRoomsExcluded');
$app->get('api/room_temp_occupied_or_excluded/{since}/{till}', 'RoomController@temporalRoomsOccupiedOrExcluded');
*/
