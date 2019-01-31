<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $table = 'videos';

    protected $fillable = [
    	'title',
    	'description'
    ];

    public $timestamps = false;

    public function actors()
    {
    	return $this->belongsToMany('App\Model\Actor','video_has_actors', 'video_id', 'actor_id');
    }

    public function tags()
    {
    	return $this->belongsToMany('App\Model\Tag','video_has_tags', 'video_id', 'tag_id');
    }

    public function categories()
    {
    	return $this->belongsToMany('App\Model\Category','video_has_categories', 'video_id', 'category_id');
    }
}
