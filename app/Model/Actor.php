<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Actor extends Model
{
    protected $table = 'actors';

    protected $fillable = [
    	'title'
    ];

    public $timestamps = false;

    public function videos()
    {
    	return $this->hasMany('App\Model\VideoActor', 'actor_id');
    }
}
