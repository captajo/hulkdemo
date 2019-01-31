<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class VideoCategory extends Model
{
    protected $table = 'video_has_categories';

    protected $fillable = [
    	'video_id',
    	'category_id'
    ];

    public $timestamps = false;
}
