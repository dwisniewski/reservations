<?php
namespace App\Http\Controllers;

use App\Room;
use App\Reservation;
use App\Timeoff;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use DateTime;

class RoomController extends Controller{
	
	public function index() {
		$rooms = Room::all();

		return response()->json($rooms);
	}

	public function getRoom(Request $request, $number) {
		//if($request->input('timeoffs'))

		$room = Room::find($number);
		if($room) {
			if($request->input('timeoffs'))
				$room = Room::find($number)->with('timeoffs')->get();
			return response()->json($room);
		}
		else
			return response('Pokój nie został odnaleziony.', 404)->header('Content-Type', 'text/html; charset=utf-8');
	}

	public function getFreeRooms($since, $till) {
		return $this->getRoomsWithGivenStatus($since, $till, 'free');
	}

	public function getOccupiedRooms($since, $till) {
		return $this->getRoomsWithGivenStatus($since, $till, 'occupied');
	}

	public function getExcludedRooms($since, $till) {
		return $this->getRoomsWithGivenStatus($since, $till, 'excluded');
	}

	public function getOccupiedOrExcludedRooms($since, $till) {
		return $this->getRoomsWithGivenStatus($since, $till, 'occupiedOrExcluded');
	}


	public function saveRoom(Request $request) {
		$room = new Room();
		
		//if(!$request->input('number'))
		//	return response()->json($this->failureMessage);

		$room->number = $request->input('number');
		$room->capacity = $request->input('capacity'); 
		$room->save();

		return response()->json($room);
	}

	public function deleteRoom($number) {
		$room = Room::find($number);
		
		if($room) {
			$room->delete();
			return response('OK', 200)->header('Content-Type', 'text/html; charset=utf-8');
		} else {
			return response('Pokój nie został odnaleziony.', 404)->header('Content-Type', 'text/html; charset=utf-8');
		}
	}

	public function updateRoom(Request $request, $number) {
		$room = Room::find($number);

		if(!$room) 
			return response('Pokój nie został odnaleziony.', 404)->header('Content-Type', 'text/html; charset=utf-8');

		if($request->input('number'))
			$room->number = $request->input('number');
		if($request->input('capacity'))
			$room->capacity = $request->input('capacity');
		
		$room->save();
		return response()->json($room);
	}

	private function verifyDate($date) {
		if($date == null)
			return true;
		$d = DateTime::createFromFormat('Y-m-d H:i:s', $date);
   		return $d && $d->format('Y-m-d H:i:s') == $date;
	}


	private function getRoomsWithGivenStatus($since, $till, $status) {
		$available_statuses = ['free', 'occupied', 'excluded', 'occupiedOrExcluded'];
		$since = $since == 'undefined' ? null : rawurldecode($since);
		$till = $till == 'undefined' ? null : rawurldecode($till);
		
		if(!$this->verifyDate($since) or !$this->verifyDate($till)) {
			return response('Podano błedny zakres czasowy!', 500)->header('Content-Type', 'text/html; charset=utf-8');
		}

		if(!in_array($status, $available_statuses)) {
			return response('Podano błędny status pokoju!', 500)->header('Content-Type', 'text/html; charset=utf-8');
		}

		$query = 'SELECT * FROM `rooms` WHERE `number` ';
		// NOT in only when selecting free rooms
		$query = $query . ($status == 'free' ? 'NOT IN' : 'IN');
		$query = $query . '(';

		// for status occupied, occupiedOrExcluded or Free - Add occupation check
		if($status != 'excluded') {
			$query = $query . 'SELECT `number` FROM `rooms`
					INNER JOIN `reservations_rooms` on `rooms`.`number` = `reservations_rooms`.`room_id`
					INNER JOIN `reservations` on `reservations`.`id` = `reservations_rooms`.`reservation_id`';

			if($since && $till) {
				$since_quoted = DB::connection()->getPdo()->quote($since);
				$till_quoted = DB::connection()->getPdo()->quote($till);
				$query = $query . sprintf(' WHERE `since` <= %s AND `till` >= %s', $till_quoted, $since_quoted);
			} else if($since) {
				$since_quoted = DB::connection()->getPdo()->quote($since);
				$query = $query . sprintf(' WHERE `till` >= %s', $since_quoted);
				
			} else if($till) {
				$till_quoted = DB::connection()->getPdo()->quote($till);
				$query = $query . sprintf(' WHERE `since` <= %s', $till_quoted);	
			}
		}

		// If subquery should be combined by merging 2 checks
		if($status == 'free' || $status == 'occupiedOrExcluded')
			$query = $query . ' UNION ALL ';

		// add excluded check for excluded, occupiedOrExcluded or Free
		if($status != 'occupied') {
			$query = $query . 'SELECT `number` FROM `rooms` INNER JOIN `rooms_unavailability` on `rooms`.`number` = `rooms_unavailability`.`room_id` ';

			if($since && $till) {
				$since_quoted = DB::connection()->getPdo()->quote($since);
				$till_quoted = DB::connection()->getPdo()->quote($till);
				$query = $query . sprintf(' WHERE `since` <= %s AND `till` >= %s', $till_quoted, $since_quoted);
			} else if($since) {
				$since_quoted = DB::connection()->getPdo()->quote($since);
				$query = $query . sprintf(' WHERE `till` >= %s', $since_quoted);
				
			} else if($till) {
				$till_quoted = DB::connection()->getPdo()->quote($till);
				$query = $query . sprintf(' WHERE `since` <= %s', $till_quoted);	
			}	
		}

		$query = $query . ');';

		$rooms_free = DB::select( DB::raw($query));
		return response()->json($rooms_free);
	}
}

?>