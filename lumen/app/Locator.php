<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Locator extends Model {
	protected $fillable = ['name', 'surname', 'email', 'phone'];
	protected $table = 'locators';
	
	public $timestamps  = false;

	public function rooms() {
		return $this->hasMany('App\Reservation', 'reservations_rooms');
	}
}

?>