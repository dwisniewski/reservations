<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model {
	protected $fillable = ['locator_id', 'reservation_time', 'since', 'till', 'is_paid', 'dinners_count', 'people_count'];
	protected $table = 'reservations';

	public $timestamps  = false;

	public function locator() {
		return $this->belongsTo('App\Locator');
	}


	//todo: verify belongs/has
	public function rooms() {
		return $this->hasMany('App\Room', 'reservations_rooms');
	}
}

?>