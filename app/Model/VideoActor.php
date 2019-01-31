<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class VideoActor extends Model
{
    protected $table = 'video_has_actors';

    protected $fillable = [
    	'video_id',
    	'actor_id'
    ];

    public $timestamps = false;
}
