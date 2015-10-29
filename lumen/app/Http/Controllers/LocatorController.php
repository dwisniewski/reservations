<?php
namespace App\Http\Controllers;

use App\Locator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LocatorController extends Controller{
	
	public function index() {
		$locators = Locator::all();

		return response()->json($locators);
	}

	public function getLocator(Request $request, $number) {
		$locator = Locator::find($number);

		if($locator) {
			if($request->input('reservations'))
				$locator = Locator::find($number)->with('reservations')->get();
			return response()->json($locator);
		}
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