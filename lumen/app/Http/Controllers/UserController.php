<?php
namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller{
	
	public function index() {
		$users = User::all();

		return response()->json($users);
	}

	public function getUser($id) {
		$user = User::find($id);

		if($user)
			return response()->json($user);
		else 
			return response('Użytkownik nie został odnaleziony.', 404)->header('Content-Type', 'text/html; charset=utf-8');
	}

	public function saveUser(Request $request) {
		$user = new User();
		
		$user->login = $request->input('login');
		$user->password = md5($request->input('password'));
		$user->email = $request->input('email');
		$user->save();
		
		return response()->json($user);
	}

	public function deleteUser($id) {
		$user = User::find($id);

		if($user) {
			$user->delete();
			return response('OK', 200)->header('Content-Type', 'text/html; charset=utf-8');
		} else {
			return response('Użytkownik nie został odnaleziony.', 404)->header('Content-Type', 'text/html; charset=utf-8');
		}
	}

	public function updateUser(Request $request, $id) {
		$user = User::find($id);

		if(!$user)
			return response('Użytkownik nie został odnaleziony.', 404);

		if($request->input('login'))
			$user->login = $request->input('login');
		if($request->input('password'))
			$user->password = md5($request->input('password'));
		if($request->input('email'))
			$user->email = $request->input('email');
		$user->save();

		return response()->json($user);
	}
}

?>