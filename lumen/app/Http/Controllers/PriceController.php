<?php
namespace App\Http\Controllers;

use App\Price;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PriceController extends Controller{
	
	public function index() {
		$prices = Price::all();

		return response()->json($prices);
	}

	public function getPrice($id) {
		$price = Price::find($id);

		if($price)
			return response()->json($price);
		else
			return response('Produkt nie został odnaleziony.', 404)->header('Content-Type', 'text/html; charset=utf-8');
	}

	public function savePrice(Request $request) {
		$price = new Price();
		
		$price->product = $request->input('product');
		$price->price = $request->input('price');
		$price->save();

		return response()->json($price);
	}

	public function deletePrice($id) {
		$price = Price::find($id);
		if($price) {
			$price->delete();
			return response('OK', 200)->header('Content-Type', 'text/html; charset=utf-8');
		} else {
			return response('Produkt nie został odnaleziony.', 404)->header('Content-Type', 'text/html; charset=utf-8');
		}
	}

	public function updatePrice(Request $request, $id) {
		$price = Price::find($id);

		if(!$price)
			return response('Produkt nie został odnaleziony.', 404);

		if($request->input('product'))
			$price->product = $request->input('product');
		if($request->input('price'))
			$price->price = $request->input('price');
		$price->save();

		return response()->json($price);
	}
}

?>