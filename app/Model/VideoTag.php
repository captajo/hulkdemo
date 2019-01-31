<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class VideoTag extends Model
{
    protected $table = 'video_has_tags';

    protected $fillable = [
    	'video_id',
    	'tag_id'
    ];

    public $timestamps = false;
}
