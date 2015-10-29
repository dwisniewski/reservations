<?php
namespace App\Http\Controllers;

use App\Reservation;
use App\Locator;
use App\Room;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReservationController extends Controller{
	
	public function index() {
		$reservations = Reservation::all();

		return response()->json($reservations);
	}

	public function getReservations(Request $request, $number) {
		$reservation = Reservation::find($number);

		if($reservation) {
			if($request->input('rooms'))
				$reservation = Reservation::find($number)->with('rooms')->get();
			return response()->json($reservation);
		}
		else
			return response('Rezerwacja nie została odnaleziona.', 404)->header('Content-Type', 'text/html; charset=utf-8');
	}

	public function saveReservation(Request $request) {
		$reservation = new Reservation();
		
		if(!$request->input('locator_id') || !Locator::find($request->input('locator_id')))
			return response('Lokator nie został sprecyzowany', 500)->header('Content-Type', 'text/html; charset=utf-8');

		if(!$request->input('rooms'))
			return response('Pokoje nie zostały sprecyzowane', 500)->header('Content-Type', 'text/html; charset=utf-8');

		foreach(explode(',', $request->input('rooms')) as $room) {
			if(!Room::find($room)) 
				return response('Pokój o numerze' .$room. 'nie jest zdefiniowany w bazie danych.', 500)->header('Content-Type', 'text/html; charset=utf-8');

		}


		$reservation->locator_id = $request->input('locator_id');
		$reservation->reservation_time = date('Y-m-d H:i:s', time());
		$reservation->since = $request->input('since');
		$reservation->till = $request->input('till');
		$reservation->is_paid = $request->input('is_paid');
		$reservation->dinners_count = $request->input('dinners_count');
		$reservation->people_count = $request->input('people_count');
		
		foreach(explode(',', $request->input('rooms')) as $room) {
			$reservation->rooms()->attach($room);
		}

		$reservation->save();

		return response()->json($reservation);
	}

	public function deleteReservation($number) {
		$reservation = Reservation::find($number);
		
		if($reservation) {
			$reservation->delete();
			return response('OK', 200)->header('Content-Type', 'text/html; charset=utf-8');
		} else {
			return response('Rezerwacja nie została odnaleziona.', 404)->header('Content-Type', 'text/html; charset=utf-8');
		}
	}

	//TODO: update rooms list if needed
	public function updateReservation(Request $request, $number) {
		$reservation = Reservation::find($number);

		if(!$reservation) 
			return response('Rezerwacja nie została odnaleziona.', 404)->header('Content-Type', 'text/html; charset=utf-8');

		if($request->input('locator_id'))
			$reservation->locator_id = $request->input('locator_id');
		if($request->input('since'))
			$reservation->since = $request->input('since');
		if($request->input('till'))
			$reservation->till = $request->input('till');
		if($request->input('id_paid'))
			$reservation->is_paid = $request->input('is_paid');
		if($request->input('dinners_count'))
			$reservation->dinners_count = $request->input('dinners_count');
		if($request->input('people_count'))
			$reservation->people_count = $request->input('people_count');

		$reservation->save();
		return response()->json($reservation);
	}
}

?>