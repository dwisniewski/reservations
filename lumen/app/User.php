<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model {
	protected $fillable = ['name', 'password', 'email'];
	protected $table = 'users';
	
	public $timestamps  = false;
}

?>