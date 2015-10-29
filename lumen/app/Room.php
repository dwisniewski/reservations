<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Room extends Model {
	protected $fillable = ['number', 'capacity'];
	protected $table = 'rooms';
    protected $primaryKey = 'number';
    
    public $incrementing = false;
	public $timestamps  = false;

	public function reservations() {
		return $this->belongsToMany('App\Reservation');
	}

	public function timeoffs() {
		return $this->hasMany('App\Timeoff');
	}


}

?>