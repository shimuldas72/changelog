<?php

namespace Sdas\Changelog\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ChangeLog extends Model
{

  	protected $table = 'change_log';
  	public $timestamps=false;
  	protected $fillable = [
  		'action_type',
	    'table_name',
	    'old_value',
	    'new_value',
	    'created_by'
  	];

    // TODO :: boot
   	// boot() function used to insert logged user_id at 'created_by' & 'updated_by'
   	public static function boot(){
       	parent::boot();
       	static::creating(function($query){
           	if(Auth::check()){
               	$query->created_by = @\Auth::user()->id;
           	}
       	});
   	}

	public function getOldValueAttribute($value)
	{
		return (array)json_decode($value);
	}

	public function getNewValueAttribute($value)
	{
		return (array)json_decode($value);
	}
}
