<?php
namespace App\Http\Controllers;

use App\Locator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class LocatorController extends Controller{
	
	public function index() {
		$locators = Locator::all();

		return response()->json($locators);
	}

	public function getLocator(Request $request, $id) {
		$locator = Locator::find($id);

		if($locator) 
			return response()->json($locator);
		else
			return response('Lokator nie został odnaleziony.', 404)->header('Content-Type', 'text/html; charset=utf-8');
	}

	public function getLocatorByPattern($token) {
		$token = $token == 'undefined' ? null : rawurldecode($token);
		$locators = Locator::where('name', 'like', $token.'%')->
							 orWhere('surname', 'like', $token.'%')->
							 orWhere('email', 'like', '%'.$token.'%')->
							 orWhere('phone', 'like', $token.'%')->get();

		if(count($locators) == 0 and preg_match('/\s/',$token)) {
			$parts = preg_split('/\s+/', $token);
			// %% looks really bad, but in PHP it's a proper way to excape % (without it sprintf thinks we want another variable to be substituted).
			$locators = DB::select( DB::raw(sprintf("SELECT * FROM locators WHERE (name like '%s%%' and surname like '%s%%') OR (name like '%s%%' and surname like '%s%%')", $parts[0], $parts[1], $parts[1], $parts[0])));
		}
		if(count($locators) > 100) 
			return response('Zbyt wiele wyników', 500)->header('Content-Type', 'text/html; charset=utf-8');
		else
			return response()->json($locators);
	}

	public function getLocatorWithReservations(Request $request, $id) {
		$locator = null;
		$type = $request->input('type');
		$time = date('Y-m-d H:i:s');

		switch($type) {
			case 'future':
				$locator = Locator::with(array('reservations' => function($q) use ($time) { $q->where('since', '>', $time); }))->
									get()->
									find($id);
				break;

			case 'old':
				$locator = Locator::with(array('reservations' => function($q) use ($time) { $q->where('since', '<', $time); }))->
									get()->
									find($id);
				break;

			default:
				$locator = Locator::with('reservations')->get()->find($id);
				break;
		}

		if($locator)
			return response()->json($locator);
		else 
			return response('Lokator nie został odnaleziony.', 404)->header('Content-Type', 'text/html; charset=utf-8');
	}

	public function saveLocator(Request $request) {
		$locator = new Locator();
		
		$locator->name = $request->input('name');
		$locator->surname = $request->input('surname');
		$locator->email = $request->input('email');
		$locator->phone = $request->input('phone'); 
		$locator->save();

		return response()->json($locator);
	}

	public function deleteLocator($number) {
		$locator = Locator::find($number);
		
		if($locator) {
			$locator->delete();
			return response('OK', 200)->header('Content-Type', 'text/html; charset=utf-8');
		} else {
			return response('Lokator nie został odnaleziony.', 404)->header('Content-Type', 'text/html; charset=utf-8');
		}
	}

	public function updateLocator(Request $request, $number) {
		$locator = Locator::find($number);

		if(!$locator) 
			return response('Lokator nie został odnaleziony.', 404)->header('Content-Type', 'text/html; charset=utf-8');

		if($request->input('name'))
			$locator->name = $request->input('name');
		if($request->input('surname'))
			$locator->surname = $request->input('surname');
		if($request->input('email'))
			$locator->email = $request->input('email');
		if($request->input('phone'))
			$locator->phone = $request->input('phone'); 

		$locator->save();
		return response()->json($locator);
	}
}

?>