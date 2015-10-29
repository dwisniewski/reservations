<?php
namespace App\Http\Controllers;

use App\Room;
use App\Timeoff;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
}

?>