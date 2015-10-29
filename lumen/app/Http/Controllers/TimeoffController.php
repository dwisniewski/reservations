<?php
namespace App\Http\Controllers;

use App\Timeoff;
use App\Room;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TimeoffController extends Controller{
	
	public function index() {
		$timeoffs = Timeoff::all();

		return response()->json($timeoffs);
	}

	public function getTimeoff($number) {
		$timeoff = Timeoff::find($number);

		if($timeoff)
			return response()->json($timeoff);
		else
			return response('Przerwa techniczna nie została odnaleziona.', 404)->header('Content-Type', 'text/html; charset=utf-8');
	}

	public function saveTimeoff(Request $request) {
		$timeoff = new Timeoff();

		if(!$request->input('room_id'))
			return response('Nie podano odpowiedniego identyfikatora pokoju.', 500);

		if(!Room::find($request->input('room_id')))
			return response('Pokój, któgo przerwa dotyczy nie istnieje w bazie.', 500);

		$timeoff->room_id = $request->input('room_id');
		$timeoff->since = $request->input('since');
		$timeoff->till = $request->input('till');
		 
		$timeoff->save();

		return response()->json($timeoff);
	}

	public function deleteTimeoff($number) {
		$timeoff = Timeoff::find($number);
		
		if($timeoff) {
			$timeoff->delete();
			return response('OK', 200)->header('Content-Type', 'text/html; charset=utf-8');
		} else {
			return response('Przerwa techniczna nie została odnaleziona.', 404)->header('Content-Type', 'text/html; charset=utf-8');
		}
	}

	public function updateTimeoff(Request $request, $number) {
		$timeoff = Timeoff::find($number);

		if(!$timeoff) 
			return response('Przerwa techniczna nie została odnaleziona.', 404)->header('Content-Type', 'text/html; charset=utf-8');

		if($request->input('room_id') && !Room::find($request->input('room_id')))
			return response('Pokój, któgo przerwa dotyczy nie istnieje w bazie.', 500);

		if($request->input('room_id'))
			$timeoff->room_id = $request->input('room_id');

		if($request->input('since'))
			$timeoff->since = $request->input('since');
		
		if($request->input('till'))
			$timeoff->till = $request->input('till');

		$timeoff->save();
		return response()->json($timeoff);
	}

}

?>