<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Timeoff extends Model {
	protected $fillable = ['room_id', 'since', 'till'];
	protected $table = 'rooms_unavailability';
	
	public $timestamps  = false;

	public function rooms() {
		return $this->belongsTo('App\Room');
	}
}

?>