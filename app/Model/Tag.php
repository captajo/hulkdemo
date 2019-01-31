<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $table = 'tags';

    protected $fillable = [
    	'title'
    ];

    public $timestamps = false;

    public function videos()
    {
    	return $this->hasMany('App\Model\VideoTag', 'tag_id');
    }
}
