<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Price extends Model {
	protected $fillable = ['product', 'price'];
	protected $table = 'prices';
	
	public $timestamps  = false;
}

?>